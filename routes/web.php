<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Admin, and Manager routes
Route::middleware(['auth', RoleMiddleware::class . ':Admin,Manager'])->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::resource('tasks', TaskController::class);
    Route::post('tasks/{task}/submit-rating', [TaskController::class, 'submitRating'])->name('tasks.submitRating');
    Route::post('projects/{project}/submit-rating', [ProjectController::class, 'submitRating'])->name('projects.submitRating');
});

// Manager-only routes (subset of capabilities)
Route::middleware(['auth', RoleMiddleware::class . ':Manager'])->group(function () {
    Route::get('/manager-projects', [ProjectController::class, 'managerProjects'])->name('projects.manager');
    Route::get('/projects/completed', [ProjectController::class, 'completed'])->name('projects.completed');
    Route::post('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.updateStatus');
    Route::get('/users/create-employee', [App\Http\Controllers\UserController::class, 'createEmployee'])->name('users.createEmployee');
    Route::post('/users/store-employee', [App\Http\Controllers\UserController::class, 'storeEmployee'])->name('users.storeEmployee');
    Route::get('/questions/create-task', [App\Http\Controllers\QuestionController::class, 'createTaskQuestion'])->name('questions.createTask');
    Route::post('/questions/store-task', [App\Http\Controllers\QuestionController::class, 'storeTaskQuestion'])->name('questions.storeTask');
});


// Admin-only routes
Route::middleware(['auth', RoleMiddleware::class . ':Admin'])->group(function () {
    Route::post('/projects/{project}/questions', [ProjectController::class, 'addQuestion'])->name('projects.addQuestion');
    Route::post('/questions/{question}/rate', [ProjectController::class, 'rateQuestion'])->name('questions.rate');
    Route::resource('users', App\Http\Controllers\UserController::class)->except(['createEmployee','storeEmployee','index']); // Create any user
    Route::resource('groups', App\Http\Controllers\GroupController::class); // Create groups
    Route::resource('questions', App\Http\Controllers\QuestionController::class); // Create project questions
    Route::resource('positions', App\Http\Controllers\PositionController::class); // Full position management
});



// Employee routes
Route::middleware(['auth', RoleMiddleware::class . ':Employee'])->group(function () {
    Route::get('/my-projects', [ProjectController::class, 'myProjects'])->name('projects.myProjects');
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('tasks.myTasks');
    Route::get('/tasks/{task}/edit-details', [TaskController::class, 'editDetails'])->name('tasks.editDetails');
    Route::patch('/tasks/{task}/update-details', [TaskController::class, 'updateDetails'])->name('tasks.updateDetails');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// General authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/notifications/{notification}/read', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return redirect()->route('tasks.show', auth()->user()->notifications()->findOrFail($id)->data['task_id']);
    })->name('notifications.read');

    // Rating views (accessible to all, but controlled by service logic)
    Route::resource('project_ratings', App\Http\Controllers\ProjectRatingController::class)->only(['index', 'show']);
    Route::resource('task_ratings', App\Http\Controllers\TaskRatingController::class)->only(['index', 'show']);
});
