<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\PartController;
use App\Http\Controllers\Admin\SkillTestConfigController;
use App\Http\Controllers\Admin\SkillTestReportController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\SkillTestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Reader & Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [ReaderController::class, 'index'])->name('reader.home');
Route::get('/topic/{slug}', [ReaderController::class, 'showTopic'])->name('reader.topic');
Route::get('/search', [ReaderController::class, 'search'])->name('reader.search');

/*
|--------------------------------------------------------------------------
| Skill Test Routes (50 Questions across all topics)
|--------------------------------------------------------------------------
*/
Route::prefix('skill-test')->name('skill-test.')->group(function () {
    Route::get('/', [SkillTestController::class, 'index'])->name('index');
    Route::post('/start', [SkillTestController::class, 'start'])->name('start');
    Route::get('/{uuid}', [SkillTestController::class, 'take'])->name('take');
    Route::post('/{uuid}/save-progress', [SkillTestController::class, 'saveProgress'])->name('save-progress');
    Route::post('/{uuid}/submit', [SkillTestController::class, 'submit'])->name('submit');
    Route::get('/{uuid}/result', [SkillTestController::class, 'result'])->name('result');
});

Route::middleware('auth')->group(function () {
    Route::post('/topic/{id}/toggle-complete', [ReaderController::class, 'toggleComplete'])->name('reader.toggle-complete');
    Route::post('/topic/{id}/toggle-bookmark', [ReaderController::class, 'toggleBookmark'])->name('reader.toggle-bookmark');
    Route::get('/bookmarks', [ReaderController::class, 'bookmarks'])->name('reader.bookmarks');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth via Socialite
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes (Guest Login & Console Logout)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin & Super Admin Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Parts & Chapters Management
    Route::resource('parts', PartController::class)->except(['show']);
    Route::resource('chapters', ChapterController::class)->except(['show']);

    // Topics Management (Existing & New Topics)
    Route::resource('topics', TopicController::class)->except(['show']);
    Route::post('topics/{topic}/exercises', [ExerciseController::class, 'store'])->name('topics.exercises.store');

    // Questions & MCQs Management
    Route::resource('exercises', ExerciseController::class)->except(['show']);
    Route::delete('topics/exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('topics.exercises.destroy');

    // Learners / Students tracking
    Route::get('students', [StudentController::class, 'index'])->name('students.index');

    // Skill Test Submissions & Performance Analytics
    Route::resource('skill-tests', SkillTestReportController::class)->only(['index', 'destroy']);
    Route::get('skill-test-settings', [SkillTestConfigController::class, 'edit'])->name('skill-test-settings.edit');
    Route::put('skill-test-settings', [SkillTestConfigController::class, 'update'])->name('skill-test-settings.update');

    // Super Admin Only: Provision & manage administrators
    Route::middleware('super_admin')->group(function () {
        Route::get('admins', [AdminManagementController::class, 'index'])->name('admins.index');
        Route::get('admins/create', [AdminManagementController::class, 'create'])->name('admins.create');
        Route::post('admins', [AdminManagementController::class, 'store'])->name('admins.store');
        Route::delete('admins/{id}', [AdminManagementController::class, 'destroy'])->name('admins.destroy');
    });
});
