<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\StudentNotificationController;
use App\Http\Controllers\StudentRegistrationController;

// Trang Welcome/Landing
Route::get('/', [AuthController::class, 'welcome'])->name('welcome');

// Routes đăng nhập chung (cho cả Admin và Student)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// UC1.2: Routes thiết lập lại mật khẩu
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Route đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Route đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// UC1.3: Đổi mật khẩu (yêu cầu đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change.submit');
});

// Dashboard thông minh: chuyển hướng theo vai trò sau khi đăng nhập
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }
    return match ($user->role ?? 'student') {
        'lecturer' => redirect()->route('lecturer.dashboard'),
        'super_admin', 'faculty_admin' => redirect()->route('admin.dashboard'),
        default => redirect()->route('student.dashboard'),
    };
})->middleware('auth')->name('dashboard');

// Student dashboard
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])
    ->middleware(['auth', 'role:student'])
    ->name('student.dashboard');

// Student OTP password reset (no auth)
Route::get('/student/forgot', [AuthController::class, 'showStudentForgotForm'])->name('student.forgot');
Route::post('/student/send-otp', [AuthController::class, 'sendStudentOtp'])->name('student.sendOtp');
Route::get('/student/verify-otp', [AuthController::class, 'showVerifyOtpForm'])->name('student.verifyOtp.form');
Route::post('/student/verify-otp', [AuthController::class, 'verifyOtpAndReset'])->name('student.verifyOtp');

// Student area (auth+role:student)
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    // Profile
    Route::get('/profile', [StudentProfileController::class, 'show'])->name('student.profile.show');
    Route::post('/profile', [StudentProfileController::class, 'update'])->name('student.profile.update');

    // Notifications
    Route::get('/notifications', [StudentNotificationController::class, 'index'])->name('student.notifications');

    // Registrations
    Route::get('/registrations/offerings', [StudentRegistrationController::class, 'offerings'])->name('student.offerings');
    Route::get('/registrations/my', [StudentRegistrationController::class, 'my'])->name('student.my');
    Route::post('/registrations/swap', [StudentRegistrationController::class, 'swap'])->name('student.swap');
    Route::post('/registrations/{classSection}', [StudentRegistrationController::class, 'register'])->name('student.register');
    Route::delete('/registrations/{registration}', [StudentRegistrationController::class, 'cancel'])->name('student.cancel');
    Route::get('/registrations/print', [StudentRegistrationController::class, 'print'])->name('student.print');

    // Timetable
    Route::get('/timetable', [StudentRegistrationController::class, 'timetable'])->name('student.timetable');
    Route::get('/timetable/ics', [StudentRegistrationController::class, 'exportIcs'])->name('student.exportIcs');
    Route::get('/timetable/export.csv', [StudentRegistrationController::class, 'exportTimetableCsv'])->name('student.timetable.exportCsv');
    Route::get('/timetable/print', [StudentRegistrationController::class, 'printTimetable'])->name('student.timetable.print');
    Route::get('/timetable/classes/{classSection}/detail-json', [StudentRegistrationController::class, 'classDetailJson'])->name('student.timetable.classDetailJson');

    // Cart removed per requirements (not part of R1/R3 core UI)
});

// API endpoints for student
Route::middleware(['auth', 'role:student'])->prefix('api/student')->group(function () {
    Route::get('/available-sections', [StudentRegistrationController::class, 'availableSectionsForSwap'])->name('api.student.available-sections');
    Route::get('/courses/{course}/sections', [StudentRegistrationController::class, 'courseSections'])->name('api.student.course-sections');
});

// Lecturer area
Route::middleware(['auth', 'role:lecturer'])->prefix('lecturer')->group(function () {
    // UC4.1: Dashboard - Thời khóa biểu giảng dạy
    Route::get('/dashboard', [\App\Http\Controllers\LecturerDashboardController::class, 'index'])->name('lecturer.dashboard');
    Route::get('/timetable/ics', [\App\Http\Controllers\LecturerDashboardController::class, 'exportIcs'])->name('lecturer.timetable.ics');
    Route::get('/timetable/print', [\App\Http\Controllers\LecturerDashboardController::class, 'printTimetable'])->name('lecturer.timetable.print');
    Route::get('/timetable/export.csv', [\App\Http\Controllers\LecturerDashboardController::class, 'exportTimetableCsv'])->name('lecturer.timetable.exportCsv');

    // UC2.8: Quản lý lớp giảng dạy
    Route::get('/classes', [\App\Http\Controllers\LecturerDashboardController::class, 'myClasses'])->name('lecturer.classes');
    Route::get('/classes/{classSection}', [\App\Http\Controllers\LecturerDashboardController::class, 'classDetail'])->name('lecturer.classes.detail');
    // AJAX detail + exports
    Route::get('/classes/{classSection}/detail-json', [\App\Http\Controllers\LecturerDashboardController::class, 'classDetailJson'])->name('lecturer.classes.detailJson');
    Route::get('/classes/{classSection}/students.csv', [\App\Http\Controllers\LecturerDashboardController::class, 'exportStudentsCsv'])->name('lecturer.classes.exportCsv');
    Route::get('/classes/{classSection}/schedule/print', [\App\Http\Controllers\LecturerDashboardController::class, 'printSchedule'])->name('lecturer.classes.print');

    // UC1.4: Hồ sơ cá nhân
    Route::get('/profile', [\App\Http\Controllers\LecturerDashboardController::class, 'profile'])->name('lecturer.profile');
    Route::post('/profile', [\App\Http\Controllers\LecturerDashboardController::class, 'updateProfile'])->name('lecturer.profile.update');

    // UC1.3/UC1.6: Đổi mật khẩu
    Route::get('/password/change', [\App\Http\Controllers\LecturerDashboardController::class, 'showChangePasswordForm'])->name('lecturer.password.change');
    Route::post('/password/change', [\App\Http\Controllers\LecturerDashboardController::class, 'changePassword'])->name('lecturer.password.change.submit');

    // UC1.7: Thông báo
    Route::get('/notifications', [\App\Http\Controllers\LecturerDashboardController::class, 'notifications'])->name('lecturer.notifications');
});

// Admin area
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/context', [AdminController::class, 'updateContext'])->name('admin.context.update');
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/profile', [AdminController::class, 'editProfile'])->name('admin.profile');
        Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // U-1 to U-6: User Management
        Route::get('/users', [AdminController::class, 'listUsers'])->name('admin.users');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        Route::post('/users/{user}/lock', [AdminController::class, 'lockUser'])->name('admin.users.lock');
        Route::post('/users/{user}/unlock', [AdminController::class, 'unlockUser'])->name('admin.users.unlock');
        Route::post('/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset_password');

        // A-1: Faculty, Rooms, Shifts Management
        Route::resource('faculties', \App\Http\Controllers\FacultyController::class);
        Route::resource('lecturers', \App\Http\Controllers\LecturerController::class)->except(['show']);
        // UC2.2 R4: Lecturer detail (read-only)
        Route::get('/lecturers/{lecturer}/detail-json', [\App\Http\Controllers\LecturerController::class, 'detailJson'])->name('lecturers.detailJson');
        // UC2.8 extensions: Lecturer assignment tools
        Route::get('/lecturers/{lecturer}/timetable-json', [\App\Http\Controllers\LecturerController::class, 'timetableJson'])->name('lecturers.timetableJson');
        Route::get('/lecturers/{lecturer}/quick-assign-candidates', [\App\Http\Controllers\LecturerController::class, 'quickAssignCandidates'])->name('lecturers.quickAssignCandidates');
        Route::resource('rooms', \App\Http\Controllers\RoomController::class)->except(['show']);
        Route::get('/rooms/{room}/detail', [\App\Http\Controllers\RoomController::class, 'getDetail'])->name('rooms.detail');
        Route::resource('shifts', \App\Http\Controllers\ShiftController::class)->except(['show']);

        // A-2: Courses with prerequisites
        Route::resource('courses', \App\Http\Controllers\CourseController::class)->except(['show']);
        Route::get('/courses/{course}/detail', [\App\Http\Controllers\CourseController::class, 'getDetail'])->name('courses.detail');
        Route::get('/courses/{course}/prerequisites', [\App\Http\Controllers\CourseController::class, 'getPrerequisites'])->name('courses.prerequisites.get');
        Route::put('/courses/{course}/prerequisites', [\App\Http\Controllers\CourseController::class, 'updatePrerequisites'])->name('courses.prerequisites.update');

        // A-3, A-4: Class Sections
        Route::get('/class-sections/{classSection}/detail', [\App\Http\Controllers\ClassSectionController::class, 'getDetail'])->name('class-sections.detail');
        Route::resource('class-sections', \App\Http\Controllers\ClassSectionController::class)->except(['show']);
        // UC2.8: Trang chuyên dùng để phân công giảng viên (mặc định lọc lớp chưa có GV)
        Route::get('/assign-lecturers', [\App\Http\Controllers\ClassSectionController::class, 'assignments'])->name('class-sections.assignments');

        // UC2.8: Assign/Change/Unassign Lecturer
        Route::get('/class-sections/{classSection}/lecturer-candidates', [\App\Http\Controllers\ClassSectionController::class, 'lecturerCandidates'])->name('class-sections.lecturer-candidates');
        Route::post('/class-sections/{classSection}/assign-lecturer', [\App\Http\Controllers\ClassSectionController::class, 'assignLecturer'])->name('class-sections.assign-lecturer');
        Route::delete('/class-sections/{classSection}/unassign-lecturer', [\App\Http\Controllers\ClassSectionController::class, 'unassignLecturer'])->name('class-sections.unassign-lecturer');

        // S-1: Registration Waves
        Route::get('/registration-waves/offerings', [\App\Http\Controllers\RegistrationWaveController::class, 'offerings'])->name('registration-waves.offerings');
        Route::get('/registration-waves/{registrationWave}/detail', [\App\Http\Controllers\RegistrationWaveController::class, 'detail'])->name('registration-waves.detail');
        Route::resource('registration-waves', \App\Http\Controllers\RegistrationWaveController::class)->except(['show']);

        // S-2: Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
        Route::get('/reports/export', [AdminController::class, 'exportReport'])->name('admin.reports.export');

        // S-3: Logs
        Route::get('/logs', [AdminController::class, 'logs'])->name('admin.logs');

        // S-4: Backup/Restore
        Route::get('/backup', [AdminController::class, 'showBackup'])->name('admin.backup');
        Route::post('/backup/create', [AdminController::class, 'backup'])->name('admin.backup.create');
        Route::post('/restore', [AdminController::class, 'restore'])->name('admin.restore');
    });
});
