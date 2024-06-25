<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Models\Lesson;

class LessonRepository
{
    public function todayLessons($teacher_id) {
        $date = date('Y-m-d');
        return Lesson::with('subject')->where('date', $date)->where('teacher_id', $teacher_id)->get();
    }

    public function todayLessonsByClass($teacher_id, $class_id) {
        $date = date('Y-m-d');
        return Lesson::with('subject')->where('date', $date)->where('teacher_id', $teacher_id)->where('class_id', $class_id)->get();
    }

    public function lessonData($lesson_id) {
        return Lesson::with('classes.students', 'teacher', 'subject')->where('id', $lesson_id)->first();
    }

    public function addLesson($theme, $date, $month, $class_id, $subject_id, $tch_id) {
        $lesson = new Lesson;
        $lesson->theme = $theme;
        $lesson->date = $date;
        $lesson->month = $month;
        $lesson->class_id = $class_id;
        $lesson->subject_id = $subject_id;
        $lesson->teacher_id = $tch_id;
        $lesson->save();
        return $lesson->id;
    }

    public function getLessonsBySubjectId($subject_id) {
        return Lesson::with('classes', 'subject')->where('subject_id', $subject_id)->latest()->get();
    }

    public function getLessonsByMonthAndSubjectId($month,$subject_id) {
        return Lesson::with('classes', 'subject')->where('month', $month)->where('subject_id', $subject_id)->latest()->get();
    }

    public function lessonMonth($subject_id) {
        return Lesson::select(DB::raw('month'))
            ->where('subject_id', $subject_id)
            ->orderBy('month')
            ->distinct()
            ->get();
    }

    public function getAll($teacher_id) {
        $ls = Lesson::where('teacher_id', $teacher_id)->get();
        return $ls;
    }

    public function filter($teacher_id, $start, $end) {
        $ls = Lesson::with('subject','classes')->whereBetween('date', [$start, $end])->where('teacher_id', $teacher_id)->get();
        return $ls;
    }
}
