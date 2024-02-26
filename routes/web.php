<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\CollegeManagementController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\facultiesController;
use App\Http\Controllers\LaboratoriesController;
use App\Http\Controllers\SubjectController;

Route::get('/', function () {
    return redirect('/login');
});

// Route::get('/register', function () {
//     return view('register');
// });

Route::group(['middleware' => 'web'], function () {
    Route::get('/login', [AuthManager::class, 'login'])->name('login')->middleware('guest');
    Route::post('/login', [AuthManager::class, 'loginPost'])->name('loginPost')->middleware('guest');
    Route::get('/google', [GoogleController::class, 'loginWithGoogle'])->name('google');
    Route::any('/google/callback', [GoogleController::class, 'callbackFromGoogle'])->name('callback');
    Route::post('/logout', [AuthManager::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'auth'], function () {

    // Dashboard Routes
    Route::get('/dashboard', [Dashboard::class, 'viewDashboard']);

    // User Routes
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersController::class, 'viewUsers'])->name('users');
        Route::post('/', [UsersController::class, 'usersPost'])->name('users.post');
        Route::put('/{id}', [UsersController::class, 'usersPut'])->name('users.put');
        Route::delete('/{id}', [UsersController::class, 'usersDelete'])->name('users.delete');
    });

    // Instructor Routes
    Route::group(['prefix' => 'faculties'], function () {
        Route::get('/', [facultiesController::class, 'viewfaculties'])->name('faculties');
        // Route::post('/', [facultiesController::class, 'facultiesPost'])->name('faculties.post');
        // Route::put('/{id}', [facultiesController::class, 'facultiesPut'])->name('faculties.put');
        // Route::delete('/{id}', [facultiesController::class, 'facultiesDelete'])->name('faculties.delete');
    });

    // Student Routes
    Route::group(['prefix' => 'students'], function () {
        Route::get('/', [UsersController::class, 'viewStudents'])->name('students');
        Route::post('/', [UsersController::class, 'studentsPost'])->name('students.post');
        Route::put('/{id}', [UsersController::class, 'studentsPut'])->name('students.put');
        Route::delete('/{id}', [UsersController::class, 'studentsDelete'])->name('students.delete');
    });

    // Attendance Routes
    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/', [AttendanceController::class, 'viewAttendance'])->name('attendance');
    });

    // Laboratories Route
    Route::group(['prefix'=> 'laboratories'], function () {
        Route::get('/', [LaboratoriesController::class,'viewLaboratories'])->name('laboratories');
        Route::post('/', [LaboratoriesController::class,'laboratoriesPost'])->name('laboratories.post');
    });

    // Subjects Route
    Route::group(['prefix' => 'subjects'], function () {
        Route::get('/', [SubjectController::class, 'viewSubjects'])->name('subjects');
        Route::post('/', [SubjectController::class, 'subjectsPost'])->name('subjects.post');
    });

    // College Management Route
    Route::group(['prefix' => 'colleges'], function () {
        Route::get('/', [CollegeManagementController::class, 'viewSubjectsAndDepartments'])->name('colleges');
        Route::post('/', [CollegeManagementController::class, 'collegesPost'])->name('colleges.post');
        Route::post('/departments', [CollegeManagementController::class, 'departmentsPost'])->name('departments.post');
    });

    // Schedules Route
    // Route::group(['prefix' => 'schedules'], function () {
    //     Route::get('/', [LaboratoriesController::class, 'viewSchedules'])->name('schedules');
    //     Route::post('/', [LaboratoriesController::class, 'schedulesPost'])->name('schedules.post');
    // });

    Route::get('/schedules', function () {
        return view('pages.schedule');
    });
});
