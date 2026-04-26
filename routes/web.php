<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\LeaveRequestController; // Jangan lupa import!

Route::get('/', function () {
    return Inertia::render('Admin/Dashboard');
});

Route::get('/admin/students', [StudentController::class, 'index'])->name('admin.students.index');

Route::post('/admin/students', [StudentController::class, 'store'])->name('admin.students.store');
Route::put('/admin/students/{student}', [StudentController::class, 'update'])->name('admin.students.update');

Route::delete('/admin/students/{student}', [StudentController::class, 'destroy'])->name('admin.students.destroy');

Route::get('/admin/attendances', [AttendanceController::class, 'index'])->name('admin.attendances.index');
Route::post('/admin/attendances', [AttendanceController::class, 'store'])->name('admin.attendances.store');

Route::get('/admin/leave-requests', [LeaveRequestController::class, 'index'])->name('admin.leave-requests.index');
Route::put('/admin/leave-requests/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus'])->name('admin.leave-requests.update-status');
