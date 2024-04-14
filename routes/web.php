<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\CollegeManagementController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\LaboratoriesController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;


Route::get('/', function () {
    return redirect('/login');
});

// Route::get('/report', function () {
//     return view('pages.report');
// });


Route::group(['middleware' => 'web'], function () {
    Route::get('/login', [AuthManager::class, 'login'])->name('login')->middleware('guest');
    Route::post('/login', [AuthManager::class, 'loginPost'])->name('loginPost')->middleware('guest');
    Route::get('/google', [GoogleController::class, 'loginWithGoogle'])->name('google');
    Route::any('/google/callback', [GoogleController::class, 'callbackFromGoogle'])->name('callback');
    Route::post('/logout', [AuthManager::class, 'logout'])->name('logout');

    // Attendance Routes
    Route::post('attendance/record-attendance', [AttendanceController::class, 'recordAttendance'])->name('record-attendance')->middleware('guest');
});

Route::group(['middleware' => ['auth', 'role:admin']], function () {

    // Laboratories Route
    Route::group(['prefix' => 'laboratories'], function () {
        Route::get('/', [LaboratoriesController::class, 'viewLaboratories'])->name('laboratories');
        Route::post('/', [LaboratoriesController::class, 'laboratoriesPost'])->name('laboratories.post');
        Route::put('/{id}', [LaboratoriesController::class, 'laboratoriesPut'])->name('laboratories.update');
        Route::delete('/{id}', [LaboratoriesController::class, 'laboratoriesDelete'])->name('laboratories.delete');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersController::class, 'viewUsers'])->name('users');
        Route::post('/', [UsersController::class, 'usersPost'])->name('users.post');
        Route::put('/{id}', [UsersController::class, 'usersPut'])->name('users.update');
        Route::delete('/{id}', [UsersController::class, 'usersDelete'])->name('users.delete');
        Route::get('/{id}', [UsersController::class, 'viewUserReports'])->name('user.report');
        Route::get('/{id}/students', [UsersController::class, 'viewUserStudents'])->name('user.students');

    });


    // College Management Route
    Route::group(['prefix' => 'colleges'], function () {
        Route::get('/', [CollegeManagementController::class, 'viewColleges'])->name('colleges');
        Route::post('/', [CollegeManagementController::class, 'createCollege'])->name('colleges.create');
        Route::put('/{id}', [CollegeManagementController::class, 'collegePut'])->name('college.update');
        Route::delete('/{id}', [CollegeManagementController::class, 'collegeDelete'])->name('college.delete');
        // Department Management Route
        Route::post('/departments', [CollegeManagementController::class, 'departmentsPost'])->name('departments.post');
        Route::put('/departments/{id}', [CollegeManagementController::class, 'departmentPut'])->name('departments.update');
        Route::delete('/departments/{id}', [CollegeManagementController::class, 'departmentDelete'])->name('departments.delete');
        // Section Management Route
        Route::post('/sections', [CollegeManagementController::class, 'sectionsPost'])->name('sections.create');
        Route::put('/sections/{id}', [CollegeManagementController::class, 'sectionPut'])->name('sections.update');
        Route::delete('/sections/{id}', [CollegeManagementController::class, 'sectionDelete'])->name('sections.delete');
    });

    // Faculty Routes
    Route::group(['prefix' => 'faculties'], function () {
        Route::get('/', [FacultyController::class, 'viewFaculties'])->name('faculties');
        Route::post('/', [FacultyController::class, 'facultiesPost'])->name('faculties.post');
        Route::put('/{id}', [FacultyController::class, 'facultiesPut'])->name('faculties.put');
        Route::delete('/{id}', [FacultyController::class, 'facultiesDelete'])->name('faculties.delete');
    });

    // Logs Route
    Route::group(['prefix' => 'logs'], function () {
        Route::get('/', [LogsController::class, 'viewLogs'])->name('logs');
        Route::get('/latest', [LogsController::class, 'latestLog']);
    });
});

// Routes for Admin and Instructor
Route::group(['middleware' => ['auth', 'role:admin,instructor']], function () {
    // Dashboard Routes
    Route::get('/dashboard', [Dashboard::class, 'viewDashboard']);

    // Attendance Routes
    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/', [AttendanceController::class, 'viewAttendance'])->name('attendance');
    });

    // Student Routes
    Route::group(['prefix' => 'students'], function () {
        Route::get('/', [StudentController::class, 'viewStudents'])->name('students');
        Route::post('/', [StudentController::class, 'studentsPost'])->name('students.post');
        Route::put('/{id}', [StudentController::class, 'studentsPut'])->name('students.put');
        Route::delete('/{id}', [StudentController::class, 'studentsDelete'])->name('students.delete');
    });

    // Subjects Route
    Route::group(['prefix' => 'subjects'], function () {
        Route::get('/', [SubjectController::class, 'viewSubjects'])->name('subjects');
        Route::post('/', [SubjectController::class, 'subjectsPost'])->name('subjects.post');
        Route::put('/{id}', [SubjectController::class, 'subjectsPut'])->name('subjects.update');
        Route::delete('/{id}', [SubjectController::class, 'subjectsDelete'])->name('subjects.delete');
    });

    // Schedules Route
    Route::group(['prefix' => 'schedules'], function () {
        Route::get('/', [ScheduleController::class, 'viewSchedules'])->name('schedules');
        Route::post('/', [ScheduleController::class, 'createSchedule'])->name('schedules.post');
        Route::put('/{id}', [ScheduleController::class, 'updateSchedule'])->name('schedules.update');
        Route::delete('/{id}', [ScheduleController::class, 'deleteSchedule'])->name('schedules.delete');
        Route::get('/user/{id}', [ScheduleController::class, 'viewUserSchedules'])->name('schedules.user');

    });

    // Profile Routes
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'viewProfile'])->name('profile');
        Route::put('/', [ProfileController::class, 'profilePut'])->name('profile.put');
        Route::put('/password', [ProfileController::class, 'passwordPut'])->name('password.put');
    });
});
