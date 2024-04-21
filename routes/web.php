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

use App\Http\Controllers\FacultyReportController;
use App\Http\Controllers\StudentReportController;
use App\Http\Controllers\UserReportController;
use App\Http\Controllers\SubjectReportController;
use App\Http\Controllers\ScheduleReportController;
use App\Http\Controllers\AttendanceReportController;


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
    // Dashboard Routes
    Route::get('/dashboard', [Dashboard::class, 'viewDashboard']);

    // Laboratories Route
    Route::group(['prefix' => 'laboratories'], function () {
        Route::get('/', [LaboratoriesController::class, 'viewLaboratories'])->name('laboratories');
        Route::post('/', [LaboratoriesController::class, 'laboratoriesPost'])->name('laboratories.post');
        Route::put('/{id}', [LaboratoriesController::class, 'laboratoriesPut'])->name('laboratories.update');
        Route::delete('/{id}', [LaboratoriesController::class, 'laboratoriesDelete'])->name('laboratories.delete');
        Route::put('/laboratories/{id}/updateLockStatus', [LaboratoriesController::class, 'updateLockStatus'])->name('updateLockStatus');
    });

    // User Routes
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersController::class, 'viewUsers'])->name('users');
        Route::post('/', [UsersController::class, 'usersPost'])->name('users.post');
        Route::put('/{id}', [UsersController::class, 'usersPut'])->name('users.update');
        Route::delete('/{id}', [UsersController::class, 'usersDelete'])->name('users.delete');
        Route::get('/{id}', [UsersController::class, 'viewUserReports'])->name('user.report');
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
        Route::get('/user/{userId}', [LogsController::class, 'logsByUser'])->name('logs.byUser');
        Route::get('/laboratory/{id}', [LogsController::class, 'viewLogsByLaboratory'])->name('logs.byLaboratory');
    });

    // Report Route
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/faculty', [FacultyReportController::class, 'index'])->name('faculty.tableReport');
        Route::get('/student', [StudentReportController::class, 'index'])->name('student.tableReport');
        Route::get('/user', [UserReportController::class, 'index'])->name('user.tableReport');
        Route::get('/subject', [SubjectReportController::class, 'index'])->name('subject.tableReport');
        // Route::get('/schedule', [ScheduleReportController::class, 'index'])->name('schedule.tableReport');
        // Route::get('/attendance', [AttendanceReportController::class, 'index'])->name('attendance.tableReport');
        // Route::get('/attendance/{sectionId}/{subjectId}', [AttendanceReportController::class, 'viewStudentAttendance'])->name('attendanceStudent.tableReport');
    });
});

// Routes for Admin and Instructor
Route::group(['middleware' => ['auth', 'role:admin,instructor']], function () {
    // // Dashboard Routes
    // Route::get('/dashboard', [Dashboard::class, 'viewDashboard']);

    // Attendance Routes
    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/', [AttendanceController::class, 'viewAttendance'])->name('attendance');
        Route::get('/{sectionId}/{subjectId}', [AttendanceController::class, 'viewStudentAttendance'])->name('attendance.student');
    });

    // Student Routes
    Route::group(['prefix' => 'students'], function () {
        Route::get('/', [StudentController::class, 'viewStudents'])->name('students');
        Route::post('/', [StudentController::class, 'studentsPost'])->name('students.post');
        Route::put('/{id}', [StudentController::class, 'studentsPut'])->name('students.put');
        Route::delete('/{id}', [StudentController::class, 'studentsDelete'])->name('students.delete');
        Route::get('/instructor/{id}', [StudentController::class, 'viewStudentsByInstructor'])->name('students.instructor');
    });

    // Subjects Route
    Route::group(['prefix' => 'subjects'], function () {
        Route::get('/', [SubjectController::class, 'viewSubjects'])->name('subjects');
        Route::post('/', [SubjectController::class, 'subjectsPost'])->name('subjects.post');
        Route::put('/{id}', [SubjectController::class, 'subjectsPut'])->name('subjects.update');
        Route::delete('/{id}', [SubjectController::class, 'subjectsDelete'])->name('subjects.delete');
        Route::get('/user/{id}', [SubjectController::class, 'viewUserSubjects'])->name('subjects.user');
        Route::get('/section/{section_id}', [SubjectController::class, 'viewSectionSubjects'])->name('subjects.section');
        Route::get('/{section_id}/attendance/{subject_id}', [ScheduleController::class, 'viewStudentAttendance'])->name('subjects.attendance');
    });

    // Schedules Route
    Route::group(['prefix' => 'schedules'], function () {
        Route::get('/', [ScheduleController::class, 'viewSchedules'])->name('schedules');
        Route::post('/', [ScheduleController::class, 'createSchedule'])->name('schedules.post');
        Route::put('/{id}', [ScheduleController::class, 'updateSchedule'])->name('schedules.update');
        Route::delete('/{id}', [ScheduleController::class, 'deleteSchedule'])->name('schedules.delete');
        Route::get('/instructor/{id}', [ScheduleController::class, 'viewUserSchedules'])->name('schedules.user');
        Route::get('/section/{section_id}', [ScheduleController::class, 'viewSectionSchedules'])->name('schedules.section');
    });


    // Profile Routes
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'viewProfile'])->name('profile');
        Route::put('/', [ProfileController::class, 'profilePut'])->name('profile.put');
        Route::put('/password', [ProfileController::class, 'passwordPut'])->name('password.put');
    });

    // Report Route
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/schedule', [ScheduleReportController::class, 'index'])->name('schedule.tableReport');
        Route::get('/attendance', [AttendanceReportController::class, 'index'])->name('attendance.tableReport');
        Route::get('/attendance/{sectionId}/{subjectId}', [AttendanceReportController::class, 'viewStudentAttendance'])->name('attendanceStudent.tableReport');
    });
});
