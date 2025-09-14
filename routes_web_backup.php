<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Enrollment Management
    Route::get('/enrollments', [AdminController::class, 'enrollmentManagement'])->name('enrollments');
    Route::get('/enrollments/show', [AdminController::class, 'showEnrollments'])->name('enrollments.show');
    Route::post('/enrollments/{enrollment}/approve', [AdminController::class, 'approveEnrollment'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject', [AdminController::class, 'rejectEnrollment'])->name('enrollments.reject');
    Route::get('/enrollments/{enrollmentId}/details', [AdminController::class, 'getEnrollmentDetails'])->name('enrollments.details');

    // User Management
    Route::get('/users', [AdminController::class, 'userManagement'])->name('users');

    // Enrolled Students
    Route::get('/enrolled-students', [AdminController::class, 'enrolledStudent'])->name('enrolled.students');
    Route::get('/students/{studentId}/payments', [AdminController::class, 'getStudentPayments'])->name('students.payments');

    // Attendance Management
    Route::get('/attendance/{session?}', [AdminController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/save', [AdminController::class, 'saveAttendance'])->name('attendance.save');
    Route::get('/attendance-records', [AdminController::class, 'attendanceRecords'])->name('attendance.records');
    Route::get('/attendance-report', [AdminController::class, 'attendanceReport'])->name('attendance.report');

    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // Schedules
    Route::get('/schedules', [AdminController::class, 'schedules'])->name('schedules');
    Route::get('/schedules/{id}', [AdminController::class, 'getSchedule'])->name('schedules.show');
    Route::put('/schedules/{id}', [AdminController::class, 'updateSchedule'])->name('schedules.update');
    Route::delete('/schedules/{id}', [AdminController::class, 'deleteSchedule'])->name('schedules.delete');
    Route::post('/schedules', [AdminController::class, 'storeSchedule'])->name('schedules.store');

    // Programs
    Route::post('/programs', [AdminController::class, 'storeProgram'])->name('programs.store');
    Route::get('/programs/{id}', [AdminController::class, 'getProgram'])->name('programs.show');
    Route::put('/programs/{id}', [AdminController::class, 'updateProgram'])->name('programs.update');
    Route::delete('/programs/{id}', [AdminController::class, 'deleteProgram'])->name('programs.delete');

    // Certificates
    Route::get('/certificates', [AdminController::class, 'certificates'])->name('certificates');
    Route::get('/certificates/{id}/pdf', [AdminController::class, 'generateCertificatePdf'])->name('certificates.pdf');

    // QR Codes
    Route::post('/qr-codes/generate', [AdminController::class, 'generateQrCodes'])->name('qr-codes.generate');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    // Add other student routes as needed
});

// Instructor Routes
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    // Add other instructor routes as needed
});

require __DIR__.'/auth.php';
