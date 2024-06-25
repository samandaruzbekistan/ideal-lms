<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\StudentRepository;
use App\Repositories\ClassesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(
        protected AdminRepository $adminRepository,
        protected TeacherRepository $teacherRepository,
        protected StudentRepository $studentRepository,
        protected ClassesRepository $classesRepository
        )
    {
    }

    public function login(Request $request){
        $admin = $this->adminRepository->getAdmin($request->login);
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

    public function getTeachers() {
        $teachers = $this->teacherRepository->getAllTeachers();
        return response()->json([
                'success' => true,
                'data' => $teachers
            ], 200);
    }

    public function registerTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'login' => 'required|string',
            'password' => 'required|string',
            'phone' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $teacher = $this->teacherRepository->getTeacher($request->login);
        if($teacher){
            return response()->json([
                'success' => false,
            ], 404);
        }
        $password = Hash::make($request->password);
        $file = $request->file('photo');
        $fileName = time().'_'.$file->getClientOriginalName();
        $path = $file->move('img/teachers/',$fileName);
        $this->teacherRepository->store($request->name, $fileName, $request->login, $password, $request->phone);
        return response()->json([
            'success' => true,
        ], 200);
    }

    public function changeTeacherPassword(Request $request) {
        $request->validate([
            'teacher_id' => 'required|numeric',
            'phone' => 'required|numeric',
            'login' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string',
        ]);
        $teacher = $this->teacherRepository->getTeacherById($request->teacher_id);
        if(!$teacher){
            return response()->json([
                'success' => false
            ], 404);
        }
        $password = Hash::make($request->password);
        $this->teacherRepository->updateData($request->teacher_id, $request->name, $request->login, $password, $request->phone);
        return response()->json([
            'success' => true,
        ], 200);
    }

    public function getTeacher(Request $request) {
        $request->validate([
            'id' => 'required|numeric'
        ]);
        $tch = $this->teacherRepository->getTeacherById($request->id);
        if($tch){
            return response()->json([
                'success' => true,
                'data' => $tch
            ]);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Ustoz topilmadi'
            ]);
        }
    }

    public function home(){
        return response()->json([
            'add' => 44
        ]);
    }

    public function allStudents() {
        $st = $this->studentRepository->getAll();
        return response()->json([
            'success' => true,
            'data' => $st
        ]);
    }

    public function getStudentsByClassId(Request $request) {
        $request->validate([
            'class_id' => 'required|numeric'
        ]);
        $st = $this->classesRepository->getClassStudents($request->class_id);
        return response()->json([
            'success' => true,
            'data' => $st
        ]);
    }

    public function addStudent(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'class_id' => 'required|numeric',
            'phone' => 'required|numeric'
        ]);
        
        $st = $this->studentRepository->getStudentByPhoneAndClassId($request->phone, $request->class_id);
        
        if($st){
            return response()->json([
                'success' => false,
                'message' => 'Bunday o\'quvchi sinfda mavjud'
            ]);
        }

        $words_array = explode(' ', $request->name);
        $result = preg_replace("/[^a-zA-Z0-9]+/", "", $words_array[0]);
        $result = strtolower($result);

        do {
            $random_number = rand(100, 999);
            $login = $result . $random_number;
            $student = $this->studentRepository->getStudentByLogin($login);
        } while ($student);

        $this->studentRepository->addStudent($request->name, $request->phone, $login, $login, $request->class_id);

        return response()->json([
            'success' => true,
            'message' => "O'quvchi qo'shildi",
            'data' => [
                'login' => $login,
                'password' => $login,
            ]
        ]);
    }
}
