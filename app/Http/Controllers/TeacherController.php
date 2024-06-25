<?php

namespace App\Http\Controllers;

use App\Repositories\LessonRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\GradeRepository;
use App\Repositories\StudentRepository;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function __construct(
        protected TeacherRepository $teacherRepository,
        protected LessonRepository $lessonRepository,
        protected SubjectRepository $subjectRepository,
        protected GradeRepository $gradeRepository,
        protected TelegramService $telegramService,
        protected StudentRepository $studentRepository,
        )
    {
    }

    public function login(Request $request){
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);
        $admin = $this->teacherRepository->getTeacher($request->login);
        if (!$admin){
            return response()->json([
                'success' => false
            ], 404);
        }
        if (Hash::check($request->input('password'), $admin->password)) {
            $admin->tokens()->delete();
            $token = $admin->createToken($request->login)->plainTextToken;
            return response()->json([
                'success' => true,
                'data' => $admin,
                'token' => $token
            ], 200);
        }
        else{
            return response()->json([
                'success' => false
            ], 404);
        }
    }

    public function myClasses(Request $request) {
        $request->validate([
            'teacher_id' => 'required|numeric'
        ]);
        $subjects = $this->subjectRepository->allByTeacherId($request->teacher_id);
        return response()->json([
            'success' => true,
            'data' => $subjects
        ], 200);
    }

    public function getAllLessons(Request $request) {
        $request->validate([
            'teacher_id' => 'required|numeric'
        ]);
        $lessons = $this->lessonRepository->getAll($request->teacher_id);
        return response()->json([
            'success' => true,
            'data' => $lessons
        ], 200);
    }

    public function getLessonsBySubjectId(Request $request) {
        $request->validate([
            'subject_id' => 'required|numeric'
        ]);
        $return_data = [];
        $month = $this->lessonRepository->lessonMonth($request->subject_id);
        $sortedLessons = $month->sortBy('month');
        $sortedLessons = $sortedLessons->reverse();
        foreach ($sortedLessons as $key => $value) {
            $return_data[] = [$value['month'],$this->lessonRepository->getLessonsByMonthAndSubjectId($value['month'], $request->subject_id)];
        }
        // $data = $this->lessonRepository->getLessonsBySubjectId($request->subject_id);
        return response()->json([
            'success' => true,
            'data' => $return_data
        ], 200);
    }


    public function filterLessons(Request $request) {
        $request->validate([
            'teacher_id' => 'required|numeric',
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
        $lessons = $this->lessonRepository->filter($request->teacher_id, $request->start, $request->end);
        return response()->json([
            'success' => true,
            'lessons' => $lessons
        ], 200);
    }

    public function getSubjectById(Request $request) {
        $request->validate([
            'subject_id' => 'required|numeric'
        ]);
        $lessons = $this->subjectRepository->find($request->subject_id);
        return response()->json([
            'success' => true,
            'data' => $lessons
        ], 200);
    }

    public function todayLessonsByClassId(Request $request) {
        $request->validate([
            'teacher_id' => 'required|numeric',
            'class_id' => 'required|numeric',
        ]);
        $lessons = $this->lessonRepository->todayLessonsByClass($request->teacher_id, $request->class_id);
        return response()->json([
            'success' => true,
            'data' => $lessons
        ], 200);
    }

    public function todayLessons(Request $request) {
        $request->validate([
            'teacher_id' => 'required|numeric'
        ]);
        $lessons = $this->lessonRepository->todayLessons($request->teacher_id);
        return response()->json([
            'success' => true,
            'data' => $lessons
        ], 200);
    }

    public function addLesson(Request $request){
        $request->validate([
            'theme' => 'required|string',
            'date' => 'required|string',
            'month' => 'required|string',
            'class_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'teacher_id' => 'required|numeric',
        ]);
        $id = $this->lessonRepository->addLesson($request->theme, $request->date, $request->month, $request->class_id, $request->subject_id, $request->teacher_id);
        if(!$id){
            return response()->json([
                'success' => false,
                'message' => 'Dars qo\'shilmadi'
            ], 200);
        }else{
            return response()->json([
                'success' => true,
                'message' => "Dars qo'shildi",
                'saved_lesson_id' => $id
            ], 200); 
        }
    }


    public function journal(Request $request) {
        $request->validate([
            'lesson_id' => 'required|numeric'
        ]);
        $lessons = $this->lessonRepository->lessonData($request->lesson_id);
        return response()->json([
            'success' => true,
            'data' => $lessons
        ], 200);
    }

    public function addGrade(Request $request) {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'lesson_id' => 'required|exists:lessons,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.grade' => 'required|numeric|min:0|max:100',
            'grades.*.comment' => 'nullable|string'
        ]);
        $lesson = $this->lessonRepository->lessonData($request->lesson_id);
        foreach ($request->grades as $gradeData) {
            $student = $this->studentRepository->getStudentById($gradeData['student_id']);
            if ($student->chat_id) {
                $text = $this->formatTelegramMessage($gradeData['grade'], $lesson, $gradeData['comment']);
                $this->telegramService->sendMessage($text, $student->chat_id);
            }
            $this->gradeRepository->store(
                $gradeData['student_id'], 
                $gradeData['grade'], 
                $request->lesson_id, 
                $request->teacher_id, 
                $gradeData['comment']
            );
        }

        return response()->json([
            'success' => true,
            'message' => "Natijalar saqlandi"
        ], 200);
    }

    private function formatTelegramMessage($grade, $lesson, $comment)
    {
        return "<b>Farzandingiz baholandi</b>\n\n" .
               "<b>Ball:</b> {$grade}\n" .
               "<b>Fan:</b> {$lesson->subject['name']}\n" .
               "<b>Mavzu:</b> {$lesson->theme}\n" .
               "<b>Ustoz:</b> {$lesson->teacher['name']}\n" .
               "<b>Izox:</b> {$comment}";
    }
}
