<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Models\Quiz;
use App\Models\Answer;

class QuizRepository
{
    public function getLessonQuizzes($lesson_id) {
        $quizzes = Quiz::with('answer')
    }
}
