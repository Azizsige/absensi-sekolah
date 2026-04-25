<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\StudentController;

Route::get('/', function () {
    return Inertia::render('Admin/Dashboard');
});

Route::get('/admin/students', [StudentController::class, 'index'])->name('admin.students.index');
