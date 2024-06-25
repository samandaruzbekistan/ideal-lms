<?php

namespace App\Http\Controllers;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\ClassesRepository;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectRepository $subjectRepository,
        protected ClassesRepository $classesRepository,
        protected TeacherRepository $teacherRepository
        )
    {
    }

    public function getAllSubjects() {
        $subjects = $this->subjectRepository->all();
        return response()->json([
            'success' => true,
            'data' => $subjects
        ]); 
    }

    public function getClassSubjects(Request $request) {
        $request->validate(['class_id' => 'required|numeric']);
        $sb = $this->subjectRepository->allByClassId($request->class_id);
        return response()->json([
            'success' => true,
            'data' => $sb
        ]); 
    }

    public function getSubjectByID(Request $request) {
        $request->validate([
            'id' => 'required|numeric'
        ]);
        $subject = $this->subjectRepository->find($request->id);
        return response()->json([
            'success' => true,
            'data' => $subject
        ]); 
    }

    public function store(Request $request) {
        // Validate incoming data
        $request->validate([
            'class_id' => 'required|integer',
            'teacher_id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        // Access the data
        $classId = $request->input('class_id');
        $teacherId = $request->input('teacher_id');
        $name = $request->input('name');

        $teacher = $this->teacherRepository->getTeacherById($teacherId);
        $classes = $this->classesRepository->getClassById($classId);

        if(!$teacher || !$classes){
            return response()->json([
                'success' => false,
                'message' => 'Sinf yoki ustoz haqida malumot topilmadi'
            ],200);
        }
        
        $id = $this->subjectRepository->store($name, $classId, $teacherId);
        $sub = $this->subjectRepository->find($id);
        return response()->json([
            'success' => true,
            'message' => 'Sinf qo\'shildi',
            'data' => $sub
        ]);
    }
}
