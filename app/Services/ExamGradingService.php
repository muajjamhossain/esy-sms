<?php

namespace App\Services;

use App\Models\ExamPaper;
use App\Models\ExamSubmission;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExamGradingService
{
    public function grade(ExamPaper $paper, ExamSubmission $submission): ?array
    {
        if (config('ai.exam_grading.provider') === 'gemini') {
            return $this->gradeWithGemini($paper, $submission);
        }

        $endpoint = config('ai.exam_grading.endpoint');
        $token = config('ai.exam_grading.token');
        if (! $endpoint || ! $token) {
            return null;
        }

        $response = Http::timeout(90)->withToken($token)->post($endpoint, [
            'max_marks' => $paper->max_marks,
            'question_file' => $this->fileAsDataUrl($paper->question_file),
            'answer_file' => $this->fileAsDataUrl($submission->answer_file),
            'answer_key_file' => $paper->answer_key_file
                ? $this->fileAsDataUrl($paper->answer_key_file)
                : null,
            'instruction' => 'Grade the answer against the question and answer key. Return JSON with marks and feedback.',
        ])->throw();

        return $this->normaliseResult($response->json(), $paper, $submission);
    }

    private function gradeWithGemini(ExamPaper $paper, ExamSubmission $submission): ?array
    {
        $key = config('ai.exam_grading.gemini_key');
        $model = config('ai.exam_grading.gemini_model');
        if (! $key || ! $model) {
            return null;
        }
        if (! $this->supportsGeminiFile($paper->question_file)
            || ! $this->supportsGeminiFile($submission->answer_file)
            || ($paper->answer_key_file && ! $this->supportsGeminiFile($paper->answer_key_file))) {
            Log::warning('Gemini grading skipped because a file is DOC/DOCX; convert it to PDF or image.', [
                'submission_id' => $submission->id,
            ]);
            return null;
        }

        $parts = [[
            'text' => 'You are a careful school examiner. Compare the student answer with the question and answer key. '
                .'Give partial credit where appropriate. Return ONLY valid JSON in this exact shape: '
                .'{"marks": number, "feedback": "short explanation"}. Maximum marks: '.$paper->max_marks,
        ]];
        $parts[] = $this->geminiFilePart($paper->question_file);
        $parts[] = $this->geminiFilePart($submission->answer_file);
        if ($paper->answer_key_file) {
            $parts[] = $this->geminiFilePart($paper->answer_key_file);
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
            .rawurlencode($model).':generateContent';
        $response = Http::timeout(120)->withHeaders([
            'X-goog-api-key' => $key,
        ])->post($url, [
            'contents' => [['role' => 'user', 'parts' => $parts]],
            'generationConfig' => ['responseMimeType' => 'application/json'],
        ])->throw();

        $text = (string) data_get($response->json(), 'candidates.0.content.parts.0.text');
        return $this->normaliseResult(json_decode($text, true), $paper, $submission);
    }

    private function normaliseResult($result, ExamPaper $paper, ExamSubmission $submission): ?array
    {
        $marks = data_get($result, 'marks');
        if (! is_numeric($marks)) {
            Log::warning('AI provider returned an invalid exam grading response.', ['submission_id' => $submission->id]);
            return null;
        }

        return [
            'marks' => min(max((float) $marks, 0), (float) $paper->max_marks),
            'feedback' => (string) data_get($result, 'feedback', ''),
        ];
    }

    private function geminiFilePart($path)
    {
        $disk = Storage::disk('public');

        return ['inline_data' => [
            'mime_type' => $disk->mimeType($path),
            'data' => base64_encode($disk->get($path)),
        ]];
    }

    private function supportsGeminiFile($path)
    {
        return ! in_array(Storage::disk('public')->mimeType($path), [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ], true);
    }

    private function fileAsDataUrl($path)
    {
        $disk = Storage::disk('public');

        return 'data:'.$disk->mimeType($path).';base64,'.base64_encode($disk->get($path));
    }
}
