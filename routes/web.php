<?php

use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceCorrectionRequestController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/attendance'));

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.break-start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.break-end');

    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
    Route::get('/attendance/detail/{attendance}', [AttendanceController::class, 'detail'])->name('attendance.detail');
    Route::post('/attendance/detail/{attendance}', [AttendanceController::class, 'requestCorrection'])->name('attendance.request-correction');

    Route::get('/stamp_correction_request/list', [AttendanceCorrectionRequestController::class, 'list'])->name('stamp-request.list');
    Route::get('/stamp_correction_request/approve/{attendanceCorrectionRequest}', [AttendanceCorrectionRequestController::class, 'approveForm'])
        ->middleware('admin')
        ->name('stamp-request.approve-form');
    Route::post('/stamp_correction_request/approve/{attendanceCorrectionRequest}', [AttendanceCorrectionRequestController::class, 'approve'])
        ->middleware('admin')
        ->name('stamp-request.approve');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'store'])->name('admin.login.store');

    Route::middleware(['auth', 'admin', 'verified'])->group(function () {
        Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.list');
        Route::get('/attendance/{attendance}', [AdminAttendanceController::class, 'detail'])->name('admin.attendance.detail');
        Route::post('/attendance/{attendance}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');
        Route::get('/staff/list', [StaffController::class, 'index'])->name('admin.staff.list');
        Route::get('/attendance/staff/{user}', [StaffController::class, 'attendance'])->name('admin.staff.attendance');
        Route::get('/attendance/staff/{user}/csv', [StaffController::class, 'csv'])->name('admin.staff.attendance.csv');
    });
});
