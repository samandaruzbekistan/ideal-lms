<?php

namespace App\Repositories;

use App\Models\Subject;

class SubjectRepository
{
    public function store($name, $classId, $teacherId)
    {
        $class = new Subject();
        $class->class_id = $classId;
        $class->teacher_id = $teacherId;
        $class->name = $name;
        $class->save();
        return $class->id;
    }

    public function update($id, $name, $classId, $teacherId)
    {
        $subject = Subject::find($id);
        $subject->class_id = $classId;
        $subject->teacher_id = $teacherId;
        $subject->name = $name;
        $subject->save();
    }

    public function delete($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
    }

    public function find($id)
    {
        return Subject::with('teacher', 'classes', 'lessons')->where('id',$id)->first();
    }

    public function all()
    {
        return Subject::with('teacher', 'classes')->orderBy('name', 'asc')->get();
    }

    public function allByClassId($id)
    {
        return Subject::with('teacher')->where('class_id',$id)->get();
    }

    public function allByTeacherId($id)
    {
        return Subject::with('classes')->where('teacher_id',$id)->get();
    }
}
