<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
