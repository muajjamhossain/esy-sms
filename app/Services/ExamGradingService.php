<?php

namespace App\Services;

use App\Models\ExamPaper;
use App\Models\ExamSubmission;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\RequestException;

class ExamGradingService
{
    public function grade(ExamPaper $paper, ExamSubmission $submission): ?array
    {
        if (config('ai.exam_grading.provider') === 'gemini') {
            return $this->gradeWithGemini($paper, $submission);
        }

        $endpoint = config('ai.exam_grading.endpoint');
        $token = config('ai.exam_grading.token');
        if (! $endpoint) {
            return null;
        }

        try {
            $request = Http::timeout(120);
            if ($token) {
                $request = $request->withToken($token);
            }
            $response = $request
                ->attach('question_file', $this->fileContents($paper->question_file), basename($paper->question_file))
                ->attach('answer_file', $this->fileContents($submission->answer_file), basename($submission->answer_file))
                ->when($paper->answer_key_file, function ($request) use ($paper) {
                    return $request->attach('answer_key_file', $this->fileContents($paper->answer_key_file), basename($paper->answer_key_file));
                })
                ->post($endpoint, ['max_marks' => $paper->max_marks]);

            return $this->normaliseResult($response->throw()->json(), $paper, $submission);
        } catch (RequestException $exception) {
            Log::warning('Exam grading service was unavailable.', [
                'submission_id' => $submission->id,
                'status' => optional($exception->response)->status(),
            ]);
            return null;
        }
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
        try {
            $response = Http::timeout(120)->withHeaders([
                'X-goog-api-key' => $key,
            ])->post($url, [
                'contents' => [['role' => 'user', 'parts' => $parts]],
                'generationConfig' => ['responseMimeType' => 'application/json'],
            ])->throw();
        } catch (RequestException $exception) {
            Log::warning('Gemini grading request failed; teacher review is required.', [
                'submission_id' => $submission->id,
                'status' => optional($exception->response)->status(),
            ]);
            return null;
        }

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

    private function fileContents($path)
    {
        return Storage::disk('public')->get($path);
    }
}
