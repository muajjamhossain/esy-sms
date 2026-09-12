# Exam OCR and grading service

This service extracts text from uploaded PDFs/images/DOCX files and optionally asks Gemini to grade the extracted answer.

```powershell
cd ai_grading
python -m venv .venv
.venv\Scripts\Activate.ps1
pip install -r requirements.txt
$env:GEMINI_API_KEY="replace-with-a-new-key"
$env:GEMINI_MODEL="gemini-flash-latest"
uvicorn app:app --host 127.0.0.1 --port 8090
```

Set the Laravel `.env` values:

```env
AI_EXAM_GRADING_PROVIDER=python
AI_EXAM_GRADING_ENDPOINT=http://127.0.0.1:8090/grade
```

Install Tesseract OCR separately and ensure `tesseract.exe` is on PATH. For Bangla handwriting, OCR quality depends on the installed `ben` language data; teacher review remains mandatory.

On Windows, PATH is not required when using:

```env
TESSERACT_CMD=C:\Program Files\Tesseract-OCR\tesseract.exe
TESSDATA_DIR=C:\Users\YOUR_USER\AppData\Local\Tesseract-OCR\tessdata
OCR_LANG=ben+eng
```

Install the Python dependencies with the same Python executable used to run Uvicorn:

```powershell
D:\laragon\bin\python\python-3.10\python.exe -m pip install -r requirements.txt
```

The Bengali and English `traineddata` files are stored in the user-local tessdata directory so administrator permission is not required.
