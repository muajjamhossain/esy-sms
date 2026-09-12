# Exam OCR and grading service

This service extracts text from uploaded PDFs/images/DOCX files and asks a local Ollama model to grade the extracted answer.

```powershell
cd ai_grading
python -m venv .venv
.venv\Scripts\Activate.ps1
pip install -r requirements.txt
$env:AI_ENGINE="ollama"
$env:OLLAMA_HOST="http://127.0.0.1:11434"
$env:OLLAMA_MODEL="llama3.2:latest"
uvicorn app:app --host 127.0.0.1 --port 8090
```

Make sure Ollama is running and the model is installed:

```powershell
ollama pull llama3.2:latest
ollama serve
```

Set the Laravel `.env` values:

```env
AI_EXAM_GRADING_PROVIDER=python
AI_EXAM_GRADING_ENDPOINT=http://127.0.0.1:8090/grade
```

Open `http://127.0.0.1:8090/health` to verify the service. For Bangla handwriting, OCR quality depends on the installed `ben` language data; teacher review remains mandatory.

On Windows, PATH is not required when using:

```env
TESSERACT_CMD='C:\Program Files\Tesseract-OCR\tesseract.exe'
TESSDATA_DIR='C:\Users\YOUR_USER\AppData\Local\Tesseract-OCR\tessdata'
OCR_LANG=ben+eng
```

Install the Python dependencies with the same Python executable used to run Uvicorn:

```powershell
D:\laragon\bin\python\python-3.10\python.exe -m pip install -r requirements.txt
```

The Bengali and English `traineddata` files are stored in the user-local tessdata directory so administrator permission is not required.
