<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\StudentController;

Route::get('/', function () {
    return Inertia::render('Admin/Dashboard');
});

Route::get('/admin/students', [StudentController::class, 'index'])->name('admin.students.index');

Route::post('/admin/students', [StudentController::class, 'store'])->name('admin.students.store');
Route::put('/admin/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');

Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');
