<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SAPA-BK (Bimbingan dan Konseling Siswa & Guru BK)
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Portal Siswa
Route::middleware(['auth', 'role:siswa,mahasiswa'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [StudentController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/create', [StudentController::class, 'createAppointment'])->name('appointment.create');
    Route::post('/appointments', [StudentController::class, 'storeAppointment'])->name('appointment.store');
    Route::get('/appointments/{id}', [StudentController::class, 'appointmentDetail'])->name('appointment.detail');
    Route::post('/appointments/{id}/cancel', [StudentController::class, 'cancelAppointment'])->name('appointment.cancel');
    Route::get('/follow-ups', [StudentController::class, 'followUps'])->name('follow_ups');
    Route::post('/follow-ups/{id}', [StudentController::class, 'updateFollowUpNotes'])->name('follow_up.update');
});

// Portal Guru BK
Route::middleware(['auth', 'role:guru_bk,konselor'])->prefix('counselor')->name('counselor.')->group(function () {
    Route::get('/dashboard', [CounselorController::class, 'dashboard'])->name('dashboard');
    Route::get('/schedules', [CounselorController::class, 'schedules'])->name('schedules');
    Route::post('/schedules', [CounselorController::class, 'storeSchedule'])->name('schedule.store');
    Route::delete('/schedules/{id}', [CounselorController::class, 'deleteSchedule'])->name('schedule.delete');
    Route::get('/appointments', [CounselorController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/{id}', [CounselorController::class, 'appointmentDetail'])->name('appointment.detail');
    Route::post('/appointments/{id}/status', [CounselorController::class, 'updateAppointmentStatus'])->name('appointment.status');
    Route::get('/appointments/{id}/session', [CounselorController::class, 'sessionView'])->name('session.view');
    Route::post('/sessions/{id}', [CounselorController::class, 'updateSession'])->name('session.update');
});

// Portal Administrator
Route::middleware(['auth', 'role:administrator,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('user.toggle');
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::post('/services', [AdminController::class, 'storeService'])->name('service.store');
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit_logs');
});
