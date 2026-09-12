<?php

return [
    'exam_grading' => [
        'provider' => env('AI_EXAM_GRADING_PROVIDER', 'gemini'),
        'endpoint' => env('AI_EXAM_GRADING_ENDPOINT'),
        'token' => env('AI_EXAM_GRADING_TOKEN'),
        'gemini_key' => env('GEMINI_API_KEY'),
        'gemini_model' => env('GEMINI_MODEL', 'gemini-flash-latest'),
    ],
];
