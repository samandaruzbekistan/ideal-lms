<?php

namespace App\Repositories;

use App\Models\Teacher;

class TeacherRepository
{
    public function getTeacher($username){
        return Teacher::where('login', $username)->first();
    }

    public function getTeacherById($id){
        return Teacher::where('id', $id)->first();
    }

    public function getAllTeachers(){
        return Teacher::orderBy('name', 'asc')->get();
    }

    public function store($name, $photo, $login, $password, $phone){
        $teacher = new Teacher;
        $teacher->name = $name;
        $teacher->photo = $photo;
        $teacher->login = $login;
        $teacher->password = $password;
        $teacher->phone = $phone;
        $teacher->save();
    }

    public function updateData($id, $name, $login, $password, $phone) {
        $teacher = Teacher::where('id', $id)->update([
            'name' => $name,
            'login' => $login,
            'password' => $password,
            'phone' => $phone,
        ]);
    }
}
