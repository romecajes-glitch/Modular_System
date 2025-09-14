<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\QRPinValidationController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\EnsureUserIsInstructor;
use App\Http\Middleware\EnsureUserIsStudent;

// Public Pages
use App\Models\Program;

Route::get('/', function () {
    $programs = Program::all();
    return view('welcome', compact('programs'));
})->name('home');

Route::get('/welcome', function () {
    $programs = Program::all();
    return view('welcome', compact('programs'));
})->name('welcome');

Route::get('/about', fn() => view('about'))->name('about');
Route::get('/programs', fn() => view('program'))->name('programs');

// Enrollment Routes
Route::get('/enrollment', function () {
    $programs = Program::all();
    return view('enrollment_form', compact('programs'));
})->name('enrollment');
Route::post('/enroll', [EnrollmentController::class, 'store'])->name('enroll.store');

// Re-apply enrollment route (for rejected students)
Route::get('/enrollment/reapply/{enrollmentId}', [EnrollmentController::class, 'reapplyForm'])->name('enrollment.reapply');
Route::post('/enrollment/reapply/{enrollmentId}', [EnrollmentController::class, 'reapplyStore'])->name('enrollment.reapply.store');

// QR PIN Validation Route
Route::post('/validate-qr-pin', [QRPinValidationController::class, 'validateQRPin'])->name('validate.qr.pin');

// Custom Login Handler
Route::post('/custom-login', [LoginController::class, 'login'])->name('custom.login');

// Admin Routes
Route::middleware(['auth', 'admin', 'check.user.status'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Admin profile photo update route
    Route::post('/profile/photo', [AdminController::class, 'updateProfilePhoto'])->name('profile.photo.update');

    // Email OTP routes for admin profile
    Route::post('/email/send-otp', [AdminController::class, 'sendEmailOTP'])->name('email.send_otp');
    Route::post('/email/verify-otp', [AdminController::class, 'verifyEmailOTP'])->name('email.verify_otp');

    // Phone number update route for admin profile
    Route::post('/phone/update', [AdminController::class, 'updatePhoneNumber'])->name('phone.update');

    // Gender update route for admin profile
    Route::post('/gender/update', [AdminController::class, 'updateGender'])->name('gender.update');

    // Birthdate update route for admin profile
    Route::post('/birthdate/update', [AdminController::class, 'updateBirthdate'])->name('birthdate.update');

    // Password update route for admin profile
    Route::post('/update-password', [AdminController::class, 'updatePassword'])->name('password.update');

    // Enrollment Management
    Route::get('/enrollments', [AdminController::class, 'enrollmentManagement'])->name('enrollments');
    Route::get('/enrollments/show', [AdminController::class, 'showEnrollments'])->name('enrollments.show');
    Route::post('/enrollments/{enrollment}/approve', [AdminController::class, 'approveEnrollment'])->name('enrollments.approve');
    Route::post('/enrollments/{enrollment}/reject', [AdminController::class, 'rejectEnrollment'])->name('enrollments.reject');
    Route::post('/enrollments/{enrollment}/update-status', [AdminController::class, 'updateEnrollmentStatus'])->name('enrollments.update-status');
    Route::get('/enrollments/{enrollmentId}/details', [AdminController::class, 'getEnrollmentDetails'])->name('enrollments.details');
    Route::get('/enrollments/{enrollmentId}/statement-of-account', [AdminController::class, 'getStatementOfAccount'])->name('enrollments.statement_of_account');

    // User Management
    Route::get('/users', [AdminController::class, 'userManagement'])->name('users');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [AdminController::class, 'getUser'])->name('users.show');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password');

    // Enrolled Students
    Route::get('/enrolled-students', [AdminController::class, 'enrolledStudent'])->name('enrolled.students');
    Route::post('/manual-enroll', [AdminController::class, 'storeManualEnrollment'])->name('manual-enroll');
    Route::get('/students/{studentId}/payments', [AdminController::class, 'getStudentPayments'])->name('students.payments');

    // Attendance Management
    Route::get('/attendance/{session?}', [AdminController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/save', [AdminController::class, 'saveAttendance'])->name('attendance.save');
    Route::post('/attendance/update-amount', [AdminController::class, 'updateAmountPaid'])->name('attendance.update-amount');
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
    Route::patch('/programs/{id}/toggle-status', [AdminController::class, 'toggleProgramStatus'])->name('programs.toggle-status');
    Route::delete('/programs/{id}', [AdminController::class, 'deleteProgram'])->name('programs.delete');

    // Certificates
    Route::get('/certificates', [AdminController::class, 'certificates'])->name('certificates');
    Route::post('/certificates/generate/{enrollmentId}', [AdminController::class, 'generateCertificate'])->name('certificates.generate');
    Route::get('/certificates/{id}/pdf', [AdminController::class, 'generateCertificatePdf'])->name('certificates.pdf');
    Route::get('/student-certificates/{studentId}', [AdminController::class, 'getStudentCertificates'])->name('student.certificates');
    Route::post('/certificates/{certificateId}/mark-done', [AdminController::class, 'markCertificateAsDone'])->name('certificates.mark-done');
    Route::get('/student-records', [AdminController::class, 'getStudentRecords'])->name('student.records');
    Route::get('/student-details/{studentId}', [AdminController::class, 'getStudentDetails'])->name('student.details');
    Route::get('/enrollment-details/{studentId}', [AdminController::class, 'getStudentEnrollmentDetails'])->name('enrollment.details');
    Route::get('/schedules/program/{programId}', [AdminController::class, 'getSchedulesForProgram'])->name('admin.schedules.program');
    Route::get('/certificate-details/{certificateId}', [AdminController::class, 'getCertificateDetails'])->name('certificate.details');

    // QR Codes
    Route::post('/qr-codes/generate', [AdminController::class, 'generateQrCodes'])->name('qr-codes.generate');
    Route::get('/qr-codes/by-date', [AdminController::class, 'getQrCodesByDate'])->name('qr-codes.by-date');
    Route::get('/qr-codes/for-date', [AdminController::class, 'getQrCodesForDate'])->name('qr-codes.for-date');

    // Update OR number
    Route::post('/enrollments/{enrollmentId}/update-or-number', [AdminController::class, 'updateOrNumber'])->name('enrollments.update.or-number');
    Route::post('/enrollments/{enrollmentId}/set-or-number', [AdminController::class, 'setOrNumber'])->name('enrollments.set.or-number');

    // Onsite Payment Verification
    Route::get('/onsite-payments/pending', [AdminController::class, 'getPendingOnsitePayments'])->name('onsite-payments.pending');
    Route::post('/onsite-payments/{enrollmentId}/confirm', [AdminController::class, 'confirmOnsitePayment'])->name('onsite-payments.confirm');
    Route::post('/onsite-payments/{enrollmentId}/reject', [AdminController::class, 'rejectOnsitePayment'])->name('onsite-payments.reject');

    // Password Verification
    Route::post('/verify-password', [AdminController::class, 'verifyPassword'])->name('verify-password');
});

// Student Routes
Route::middleware(['auth', EnsureUserIsStudent::class, 'check.user.status'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/attendance', [StudentController::class, 'attendance'])->name('attendance');
    Route::get('/payment', [StudentController::class, 'payment'])->name('payment');
    Route::get('/certificate', [StudentController::class, 'certificate'])->name('certificate');
    Route::post('/profile/photo', [StudentController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::post('/send-email-otp', [StudentController::class, 'sendEmailOtp'])->name('send-email-otp');
    Route::post('/verify-email-otp', [StudentController::class, 'verifyEmailOtp'])->name('verify-email-otp');
    Route::post('/change-password', [StudentController::class, 'changePassword'])->name('change-password');
    Route::get('/attendance/ajax', [StudentController::class, 'ajaxAttendance'])->name('attendance.ajax');
});

// Payment Routes
Route::middleware(['auth', EnsureUserIsStudent::class, 'check.user.status'])->group(function () {
    Route::get('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    Route::post('/payment/direct', [StudentController::class, 'processPayment'])->name('payment.direct');
});

// Instructor Routes
Route::middleware(['auth', EnsureUserIsInstructor::class, 'check.user.status'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    Route::get('/students', [InstructorController::class, 'students'])->name('students');
    Route::get('/students/{id}', [InstructorController::class, 'viewStudent'])->name('students.view');
    Route::get('/attendance', [InstructorController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/save', [InstructorController::class, 'saveAttendance'])->name('attendance.save');
    Route::post('/attendance/update-amount', [InstructorController::class, 'updateAmountPaid'])->name('attendance.update-amount');
    Route::get('/certificate', [InstructorController::class, 'certificates'])->name('certificates');
    Route::get('/program/{id}', [InstructorController::class, 'programDetails'])->name('program.details');
    Route::post('/enrollments/{enrollmentId}/update-or-number', [InstructorController::class, 'updateOrNumber'])->name('enrollments.update.or-number');
    Route::get('/schedules/program/{programId}', [InstructorController::class, 'getSchedulesForProgram'])->name('schedules.program');

    // New routes for attendance template download and upload
    Route::get('/attendance/template/download', [InstructorController::class, 'downloadAttendanceTemplate'])->name('attendance.template.download');
    Route::post('/attendance/template/upload', [InstructorController::class, 'uploadAttendanceTemplate'])->name('attendance.template.upload');
});

// Authenticated User Profile Routes
Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Generic dashboard route that redirects based on user role
Route::middleware(['auth', 'check.user.status'])->get('/dashboard', function () {
    $user = Auth::user();

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'instructor':
            return redirect()->route('instructor.dashboard');
        case 'student':
            return redirect()->route('student.dashboard');
        default:
            abort(403, 'Unauthorized role');
    }
})->name('dashboard');

// Auth Routes (Login, Register, etc.)
require __DIR__ . '/auth.php';
