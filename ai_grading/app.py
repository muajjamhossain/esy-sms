"""OCR and optional AI grading microservice for exam answer sheets.

Run:
    pip install -r requirements.txt
    uvicorn app:app --host 127.0.0.1 --port 8090
"""

import io
import json
import os
from pathlib import Path
from typing import Optional

import httpx
from fastapi import FastAPI, File, Form, HTTPException, UploadFile
from PIL import Image
import pytesseract

app = FastAPI(title="Exam OCR and Grading Service")

TESSERACT_CMD = os.getenv(
    "TESSERACT_CMD",
    r"C:\Program Files\Tesseract-OCR\tesseract.exe",
)
pytesseract.pytesseract.tesseract_cmd = TESSERACT_CMD


@app.get("/health")
def health():
    try:
        version = pytesseract.get_tesseract_version()
    except (pytesseract.TesseractNotFoundError, OSError) as error:
        raise HTTPException(status_code=503, detail=f"Tesseract is unavailable: {error}")

    return {"status": "ok", "tesseract": str(version).splitlines()[0]}


def extract_text(filename: str, content: bytes) -> str:
    suffix = Path(filename).suffix.lower()
    if suffix == ".pdf":
        from pypdf import PdfReader

        reader = PdfReader(io.BytesIO(content))
        return "\n".join(page.extract_text() or "" for page in reader.pages)
    if suffix in {".jpg", ".jpeg", ".png", ".webp"}:
        return pytesseract.image_to_string(Image.open(io.BytesIO(content)), lang=os.getenv("OCR_LANG", "eng"))
    if suffix == ".docx":
        from docx import Document

        return "\n".join(paragraph.text for paragraph in Document(io.BytesIO(content)).paragraphs)
    raise HTTPException(status_code=422, detail="Use PDF, DOCX, JPG, JPEG, PNG, or WEBP files.")


async def grade_with_gemini(question: str, answer: str, key: Optional[str], max_marks: float, answer_key: str) -> dict:
    if not key:
        raise RuntimeError("GEMINI_API_KEY is not configured.")
    model = os.getenv("GEMINI_MODEL", "gemini-flash-latest")
    prompt = (
        "Grade the student's answer against the question and answer key. Give partial credit. "
        'Return only JSON: {"marks": number, "feedback": "short explanation"}.\n'
        f"Maximum marks: {max_marks}\nQUESTION:\n{question}\nANSWER KEY:\n{answer_key}\nSTUDENT ANSWER:\n{answer}"
    )
    url = f"https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent"
    async with httpx.AsyncClient(timeout=120) as client:
        response = await client.post(
            url,
            headers={"X-goog-api-key": key},
            json={
                "contents": [{"parts": [{"text": prompt}]}],
                "generationConfig": {"responseMimeType": "application/json"},
            },
        )
        response.raise_for_status()
    text = response.json()["candidates"][0]["content"]["parts"][0]["text"]
    result = json.loads(text)
    result["marks"] = min(max(float(result["marks"]), 0), max_marks)
    return result


@app.post("/grade")
async def grade(
    question_file: UploadFile = File(...),
    answer_file: UploadFile = File(...),
    answer_key_file: Optional[UploadFile] = File(None),
    max_marks: float = Form(...),
):
    question = extract_text(question_file.filename or "question.pdf", await question_file.read())
    answer = extract_text(answer_file.filename or "answer.pdf", await answer_file.read())
    answer_key = ""
    if answer_key_file:
        answer_key = extract_text(answer_key_file.filename or "key.pdf", await answer_key_file.read())

    try:
        result = await grade_with_gemini(
            question,
            answer,
            os.getenv("GEMINI_API_KEY"),
            max_marks,
            answer_key,
        )
    except (httpx.HTTPError, KeyError, ValueError, json.JSONDecodeError, RuntimeError) as error:
        return {"marks": None, "feedback": f"OCR completed; teacher review required: {error}"}

    return result
