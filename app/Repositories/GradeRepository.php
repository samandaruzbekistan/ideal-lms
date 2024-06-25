<?php

namespace App\Repositories;

use App\Models\Grade;

class GradeRepository
{
    public function store($student_id, $grade, $lesson_id, $teacher_id, $comment = null) {
        $gr = new Grade;
        $gr->student_id = $student_id;
        $gr->grade = $grade;
        $gr->lesson_id = $lesson_id;
        $gr->teacher_id = $teacher_id;
        $gr->comment = $comment;
        $gr->save();
    }
}
