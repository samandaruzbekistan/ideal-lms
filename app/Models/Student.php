<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function grades()
    {
        return $this->belongsTo(Grade::class, 'student_id');
    }
}
