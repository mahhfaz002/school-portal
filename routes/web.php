<?php

use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\AdmissionDashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\TransportationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\TimetableController;
use Illuminate\Support\Facades\Route;

// ==========================================
// 1. PUBLIC ROUTES (No Login Required)
// ==========================================
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');

// Admission Form (Prospective Students)
Route::get('/apply', [ApplicantController::class, 'showForm'])->name('admission.form');
Route::post('/apply', [ApplicantController::class, 'submit'])->name('admission.submit');


// ==========================================
// 2. PASSWORD SECURITY CHECK (Login Required)
// ==========================================
// These routes MUST be outside the 'force.password.change' middleware
// to avoid the infinite redirect loop.
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordChangeController::class, 'showChangeForm'])->name('password.change.notice');
    Route::post('/change-password', [PasswordChangeController::class, 'updatePassword'])->name('password.change.update');
});


// ==========================================
// 3. FULLY PROTECTED ROUTES (Locked until Password Changed)
// ==========================================
Route::middleware(['auth', 'verified', 'force.password.change'])->group(function () {

    // --- Core Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Principal/Proprietor Specifics ---
    Route::post('/principal/add-teacher', [DashboardController::class, 'storeTeacher'])->name('teacher.store');

    // --- Admission Management ---
    Route::get('/admin/dashboard', [AdmissionDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/applicant/{id}/status', [AdmissionDashboardController::class, 'updateStatus'])->name('admin.updateStatus');

    // --- User Profile ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Student Management ---
    Route::resource('students', StudentController::class);
    Route::get('/students/{student}/report-card', [StudentController::class, 'reportCard'])->name('students.report');
    Route::post('/students/{student}/remark', [StudentController::class, 'saveRemark'])->name('students.remark');
    Route::get('/students/{student}/id-card', [StudentController::class, 'idCard'])->name('students.id-card');
    Route::get('/promotion', [StudentController::class, 'promotionForm'])->name('students.promotion');
    Route::post('/promotion', [StudentController::class, 'promote'])->name('students.promote');

    // --- Academic & Results ---
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/subjects', [SubjectController::class, 'store'])->name('subjects.store');
    Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

    // Score entry (class + subject sheet). Single canonical route name.
    Route::middleware(['role:teacher,exam_officer,principal,proprietor,admin'])->group(function () {
        Route::get('/scores/entry', [ScoreController::class, 'create'])->name('scores.create');
        Route::post('/scores/store', [ScoreController::class, 'store'])->name('scores.store');
    });

    // --- Fees & Finance ---
    Route::get('/students/{student}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/students/{student}/pay', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'show'])->name('payments.receipt');

    // --- Attendance ---
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');

    // --- Reports ---
    Route::get('/reports/download/{studentId}', [ReportController::class, 'downloadPdf'])->name('reports.download');

    // --- Announcements / Communications (everyone can read) ---
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::middleware(['role:proprietor,principal,admin,ict'])->group(function () {
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        // --- School Settings / Branding ---
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // --- Timetable (staff) ---
    Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');
    Route::post('/timetable', [TimetableController::class, 'store'])->name('timetable.store');

    // --- Library ---
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/library/issue', [LibraryController::class, 'issueBook'])->name('library.issue');
    Route::post('/library/return/{record}', [LibraryController::class, 'returnBook'])->name('library.return');

    // --- Exams / Quizzes ---
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::post('/exams/{quiz}/submit', [ExamController::class, 'submitQuiz'])->name('exams.submit');

    // --- Management-only modules: Transport, HR/Payroll, Alumni ---
    Route::middleware(['role:proprietor,principal,admin,ict,accountant'])->group(function () {
        Route::get('/transport', [TransportationController::class, 'index'])->name('transport.index');
        Route::post('/transport/assign', [TransportationController::class, 'assignStudent'])->name('transport.assign');

        Route::get('/hr', [PayrollController::class, 'index'])->name('hr.index');
        Route::post('/payroll/generate/{month}', [PayrollController::class, 'generatePayroll'])->name('payroll.generate');

        Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
        Route::get('/alumni/search', [AlumniController::class, 'search'])->name('alumni.search');
        Route::post('/alumni/register', [AlumniController::class, 'register'])->name('alumni.register');
    });

    // --- ROLE-BASED ACCESS (Sub-Groups) ---

    // Principal & Proprietor
    Route::middleware(['role:principal,proprietor'])->group(function () {
        Route::get('/staff', [UserController::class, 'index'])->name('staff.index');
        Route::post('/staff/{user}/assign', [UserController::class, 'assignClass'])->name('staff.assign');
    });

    // Admin, ICT, Principal & Proprietor
    Route::middleware(['role:principal,ict,proprietor,admin'])->group(function () {
        Route::post('/staff', [UserController::class, 'store'])->name('staff.store');
    });

    // Superadmin Switching
    Route::get('/superadmin/switchboard', [DashboardController::class, 'switchboard'])
        ->name('superadmin.switchboard')
        ->middleware('role:proprietor,ict');

    // Inventory & Detailed Admissions
    Route::middleware(['role:admin,ict,proprietor'])->group(function () {
        Route::resource('inventory', InventoryItemController::class);
        Route::get('/admin/admissions', [ApplicantController::class, 'index'])->name('admission.admin');
        Route::post('/admin/admissions/{id}/approve', [ApplicantController::class, 'approve'])->name('admission.approve');
        Route::post('/admin/admissions/{id}/reject', [ApplicantController::class, 'reject'])->name('admission.reject');
    });
});

// ==========================================
// AUTHENTICATION SYSTEM
// ==========================================
require __DIR__.'/auth.php';