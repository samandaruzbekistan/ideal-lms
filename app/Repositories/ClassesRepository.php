<?php

namespace App\Repositories;

use App\Models\Classes;

class ClassesRepository
{
    public function getAll() {
        return Classes::with('teacher')->orderBy('name', 'asc')->get();
    }

    public function getClassById($id) {
        return Classes::with('teacher')->find($id);
    }

    public function getClassStudents($id) {
        return Classes::with('teacher', 'students')->find($id);
    }

    public function getClassByName($name) {
        return Classes::with('teacher')->where('name', $name)->first();
    }

    public function update($name, $level, $teacher_id, $class_id) {
        $cl = Classes::find($class_id);
        $cl->name = $name;
        $cl->level = $level;
        $cl->teacher_id = $teacher_id;
        $cl->save();
    }

    public function addClass($name, $level, $teacher_id) {
        $cl = new Classes;
        $cl->name = $name;
        $cl->level = $level;
        $cl->teacher_id = $teacher_id;
        $cl->save();
    }
}
