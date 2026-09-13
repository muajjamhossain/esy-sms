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
MAX_EXTRACTED_CHARS = int(os.getenv("MAX_EXTRACTED_CHARS", "12000"))

TESSERACT_CMD = os.getenv(
    "TESSERACT_CMD",
    r"C:\Program Files\Tesseract-OCR\tesseract.exe",
)
TESSDATA_DIR = os.getenv(
    "TESSDATA_DIR",
    os.path.expandvars(r"%LOCALAPPDATA%\Tesseract-OCR\tessdata"),
)
pytesseract.pytesseract.tesseract_cmd = TESSERACT_CMD
os.environ["TESSDATA_PREFIX"] = TESSDATA_DIR


@app.get("/")
def index():
    return {"service": "exam-ocr-grading", "status": "ok", "health": "/health", "grading": "/grade"}


@app.get("/health")
def health():
    try:
        version = pytesseract.get_tesseract_version()
    except (pytesseract.TesseractNotFoundError, OSError) as error:
        raise HTTPException(status_code=503, detail=f"Tesseract is unavailable: {error}")

    return {
        "status": "ok",
        "tesseract": str(version).splitlines()[0],
        "ocr_lang": os.getenv("OCR_LANG", "ben+eng"),
        "ai_engine": os.getenv("AI_ENGINE", "ollama"),
    }


def extract_text(filename: str, content: bytes) -> str:
    suffix = Path(filename).suffix.lower()
    if suffix == ".pdf":
        from pypdf import PdfReader

        reader = PdfReader(io.BytesIO(content))
        text = "\n".join(page.extract_text() or "" for page in reader.pages)
        return text[:MAX_EXTRACTED_CHARS]
    if suffix in {".jpg", ".jpeg", ".png", ".webp"}:
        return pytesseract.image_to_string(
            Image.open(io.BytesIO(content)),
            lang=os.getenv("OCR_LANG", "ben+eng"),
        )[:MAX_EXTRACTED_CHARS]
    if suffix == ".docx":
        from docx import Document

        text = "\n".join(paragraph.text for paragraph in Document(io.BytesIO(content)).paragraphs)
        return text[:MAX_EXTRACTED_CHARS]
    raise HTTPException(status_code=422, detail="Use PDF, DOCX, JPG, JPEG, PNG, or WEBP files.")


async def call_ollama_chat(model: str, messages: list, max_marks: float) -> dict:
    host = os.getenv("OLLAMA_HOST", "http://127.0.0.1:11434").rstrip("/")
    async with httpx.AsyncClient(timeout=90) as client:
        response = await client.post(
            f"{host}/api/chat",
            json={
                "model": model,
                "messages": messages,
                "format": "json",
                "stream": False,
                "options": {
                    "temperature": float(os.getenv("OLLAMA_TEMPERATURE", "0")),
                    "num_predict": int(os.getenv("OLLAMA_NUM_PREDICT", "256")),
                },
            },
        )
        response.raise_for_status()

    content = response.json().get("message", {}).get("content")
    if not content:
        raise ValueError("Ollama response did not contain message content.")
    result = json.loads(content)
    if not isinstance(result.get("marks"), (int, float)):
        raise ValueError("Ollama response did not contain numeric marks.")
    return {
        "marks": min(max(float(result["marks"]), 0), max_marks),
        "feedback": str(result.get("feedback", "")),
    }


async def grade_with_ollama(question: str, answer: str, max_marks: float, answer_key: str) -> dict:
    prompt = (
        "Grade the student's answer against the question and answer key. Give partial credit. "
        'Return only JSON: {"marks": number, "feedback": "short explanation"}.\n'
        f"Maximum marks: {max_marks}\nQUESTION:\n{question}\nANSWER KEY:\n{answer_key}\nSTUDENT ANSWER:\n{answer}"
    )
    return await call_ollama_chat(
        os.getenv("OLLAMA_MODEL", "llama3.2:latest"),
        [{"role": "user", "content": prompt}],
        max_marks,
    )


@app.post("/grade")
async def grade(
    question_file: UploadFile = File(...),
    answer_file: UploadFile = File(...),
    answer_key_file: Optional[UploadFile] = File(None),
    max_marks: float = Form(...),
):
    try:
        question = extract_text(question_file.filename or "question.pdf", await question_file.read())
        answer = extract_text(answer_file.filename or "answer.pdf", await answer_file.read())
    except (pytesseract.TesseractError, OSError) as error:
        return {"marks": None, "feedback": f"OCR failed; teacher review required: {error}"}
    answer_key = ""
    if answer_key_file:
        try:
            answer_key = extract_text(answer_key_file.filename or "key.pdf", await answer_key_file.read())
        except (pytesseract.TesseractError, OSError) as error:
            return {"marks": None, "feedback": f"OCR failed; teacher review required: {error}"}

    try:
        result = await grade_with_ollama(question, answer, max_marks, answer_key)
    except (httpx.HTTPError, KeyError, ValueError, json.JSONDecodeError, RuntimeError) as error:
        return {"marks": None, "feedback": f"OCR completed; teacher review required: {error}"}

    return result
