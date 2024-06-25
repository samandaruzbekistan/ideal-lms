<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function getAll() {
        $st = Student::with('classes.teacher')->orderBy('name', 'asc')->get();
        return $st;
    }

    public function getStudentByPhoneAndClassId($arg, $class_id) {
        return Student::where('phone', $arg)->where('class_id', $class_id)->first();
    }

    public function getStudentById($arg) {
        return Student::with('classes')->where('id', $arg)->first();
    }

    public function getStudentByClassId($arg) {
        return Student::with('classes')->where('class_id', $arg)->get();
    }

    public function getStudentByName($arg) {
        return Student::with('classes')->where('name', $arg)->first();
    }

    public function getStudentByLogin($arg) {
        return Student::with('classes')->where('login', $arg)->first();
    }

    public function addStudent($name, $phone, $login, $password, $class_id) {
        $st = new Student;
        $st->name = $name;
        $st->phone = $phone;
        $st->login = $login;
        $st->password = $password;
        $st->class_id = $class_id;
        $st->save();
    }
}
