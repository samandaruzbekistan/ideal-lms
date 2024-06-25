<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('admin')->group(function () {
    Route::post('login', [AdminController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function (){
        Route::get('teachers', [AdminController::class, 'getTeachers']);
        Route::post('get-teacher', [AdminController::class, 'getTeacher']);
        Route::post('add-teacher', [AdminController::class, 'registerTeacher']);
        Route::post('update-teacher', [AdminController::class, 'changeTeacherPassword']);

        Route::get('all-students', [AdminController::class, 'allStudents']);
        Route::post('students-by-class', [AdminController::class, 'getStudentsByClassId']);
        Route::post('add-student', [AdminController::class, 'addStudent']);

        Route::get('all-classes', [ClassesController::class, 'getAllClasses']);
        Route::post('get-classes', [ClassesController::class, 'getClassById']);
        Route::post('add-classes', [ClassesController::class, 'addClasses']);
        Route::post('update-class', [ClassesController::class, 'update']);

        Route::get('subjects', [SubjectController::class, 'getAllSubjects']);
        Route::post('subject', [SubjectController::class, 'getSubjectByID']);
        Route::post('add-subject', [SubjectController::class, 'store']);
        Route::post('get-class-subjects', [SubjectController::class, 'getClassSubjects']);
    });
});



Route::prefix('teacher')->group(function () {
    Route::post('login', [TeacherController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function (){
        Route::post('today-lessons', [TeacherController::class, 'todayLessons']);
        Route::post('add-lesson', [TeacherController::class, 'addLesson']);
        Route::post('my-classes', [TeacherController::class, 'myClasses']);
        Route::post('get-all-lessons', [TeacherController::class, 'getAllLessons']);
        Route::post('filter-lessons', [TeacherController::class, 'filterLessons']);
        Route::post('get-subject', [TeacherController::class, 'getSubjectById']);
        Route::post('today-lessons-by-class', [TeacherController::class, 'todayLessonsByClassId']);
        Route::post('get-lessons-by-subject', [TeacherController::class, 'getLessonsBySubjectId']);

        Route::post('journal', [TeacherController::class, 'journal']);
        Route::post('add-grade', [TeacherController::class, 'addGrade']);
        

        Route::get('all-classes', [ClassesController::class, 'getAllClasses']);
        Route::post('get-classes', [ClassesController::class, 'getClassById']);
        Route::post('add-classes', [ClassesController::class, 'addClasses']);
        Route::post('update-class', [ClassesController::class, 'update']);

        Route::get('subjects', [SubjectController::class, 'getAllSubjects']);
        Route::post('subject', [SubjectController::class, 'getSubjectByID']);
        Route::post('add-subject', [SubjectController::class, 'store']);
        Route::post('get-class-subjects', [SubjectController::class, 'getClassSubjects']);
    });
});
