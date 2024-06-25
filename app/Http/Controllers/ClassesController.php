<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AdminRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\ClassesRepository;

class ClassesController extends Controller
{
    public function __construct(
        protected AdminRepository $adminRepository,
        protected TeacherRepository $teacherRepository,
        protected ClassesRepository $classesRepository,
        )
    {
    }

    public function getAllClasses() {
        $cl = $this->classesRepository->getAll();
        return response()->json([
            'success' => true,
            'data' => $cl
        ]);
    }

    public function getClassById(Request $request) {
        $request->validate([
            'id' => 'required|numeric'
        ]);
        $cl = $this->classesRepository->getClassById($request->id);
        return response()->json([
            'success' => true,
            'data' => $cl
        ]);
    }

    public function addClasses(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'teacher_id' => 'required|numeric',
            'level' => 'required|numeric',
        ]);
        $cl = $this->classesRepository->getClassByName($request->name);
        $t = $this->teacherRepository->getTeacherById($request->teacher_id);
        if($cl || !$t){
            return response()->json([
                'success'=>false
            ]);
        }
        $this->classesRepository->addClass($request->name, $request->level, $request->teacher_id);
        return response()->json([
            'success'=>true
        ],200);
    }

    public function update(Request $request) {
        $request->validate([
            'class_id' => 'required|numeric',
            'level' => 'required|numeric',
            'teacher_id' => 'required|numeric',
            'name' => 'required|string',
        ]);
        $this->classesRepository->update($request->name, $request->level, $request->teacher_id, $request->class_id);
        return response()->json([
            'success'=>true
        ],200);
    }
}
