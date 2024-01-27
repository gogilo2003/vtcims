<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V1\HomeController;
use App\Http\Controllers\V1\TermController;
use App\Http\Controllers\V1\CourseController;
use App\Http\Controllers\V1\IntakeController;
use App\Http\Controllers\V1\ProgramController;
use App\Http\Controllers\V1\SponsorController;
use App\Http\Controllers\V1\StudentController;
use App\Http\Controllers\V1\SubjectController;
use App\Http\Controllers\V1\AllocationController;
use App\Http\Controllers\V1\DepartmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::get('/', function () {
    //     // return Inertia::render('Welcome', [
    //     //     'canLogin' => Route::has('login'),
    //     //     'canRegister' => Route::has('register'),
    //     //     'laravelVersion' => Application::VERSION,
    //     //     'phpVersion' => PHP_VERSION,
    //     // ]);
    //     return Inertia::render('Dashboard');
    // });

    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::prefix('students')->name('students')->group(function () {
        Route::get('', [StudentController::class, 'index']);
        Route::post('', [StudentController::class, 'store'])->name('-store');
        Route::patch('{student}', [StudentController::class, 'update'])->name('-update');
        Route::delete('{student}', [StudentController::class, 'destroy'])->name('-destroy');
        Route::post('photo/{student}', [StudentController::class, 'picture'])->name('-photo');
    });

    Route::prefix('staff')->name('staff')->group(function () {
        Route::get('', [StudentController::class, 'index']);
        Route::post('', [StudentController::class, 'store'])->name('-store');
        Route::patch('{student}', [StudentController::class, 'update'])->name('-update');
        Route::delete('{student}', [StudentController::class, 'destroy'])->name('-destroy');
        Route::post('photo/{student}', [StudentController::class, 'picture'])->name('-photo');
    });

    Route::prefix('subjects')->name('subjects')->group(function () {
        Route::get('', [SubjectController::class, 'index']);
        Route::post('', [SubjectController::class, 'store'])->name('-store');
        Route::patch('{subject}', [SubjectController::class, 'update'])->name('-update');
        Route::delete('{subject}', [SubjectController::class, 'destroy'])->name('-destroy');
    });

    Route::prefix('allocations')->name('allocations')->group(function () {
        Route::get('', [AllocationController::class, 'index']);
        Route::post('', [AllocationController::class, 'store'])->name('-store');
        Route::patch('{allocation}', [AllocationController::class, 'update'])->name('-update');
        Route::delete('{allocation}', [AllocationController::class, 'destroy'])->name('-destroy');
    });

    Route::prefix('departments')->name('departments')->group(function () {
        Route::get('', [DepartmentController::class, 'index']);
        Route::post('', [DepartmentController::class, 'store'])->name('-store');
        Route::patch('{department}', [DepartmentController::class, 'update'])->name('-update');
        Route::delete('{department}', [DepartmentController::class, 'destroy'])->name('-destroy');
    });
    Route::prefix('courses')->name('courses')->group(function () {
        Route::get('', [CourseController::class, 'index']);
        Route::post('', [CourseController::class, 'store'])->name('-store');
        Route::patch('{course}', [CourseController::class, 'update'])->name('-update');
        Route::delete('{course}', [CourseController::class, 'destroy'])->name('-destroy');
    });
    Route::prefix('programs')->name('programs')->group(function () {
        Route::get('', [ProgramController::class, 'index']);
        Route::post('', [ProgramController::class, 'store'])->name('-store');
        Route::patch('{program}', [ProgramController::class, 'update'])->name('-update');
        Route::delete('{program}', [ProgramController::class, 'destroy'])->name('-destroy');
    });
    Route::prefix('sponsors')->name('sponsors')->group(function () {
        Route::get('', [SponsorController::class, 'index']);
        Route::post('', [SponsorController::class, 'store'])->name('-store');
        Route::patch('{sponsor}', [SponsorController::class, 'update'])->name('-update');
        Route::delete('{sponsor}', [SponsorController::class, 'destroy'])->name('-destroy');
    });
    Route::prefix('intakes')->name('intakes')->group(function () {
        Route::get('', [IntakeController::class, 'index']);
        Route::post('', [IntakeController::class, 'store'])->name('-store');
        Route::patch('{intake}', [IntakeController::class, 'update'])->name('-update');
        Route::delete('{intake}', [IntakeController::class, 'destroy'])->name('-destroy');
    });
    Route::prefix('terms')->name('terms')->group(function () {
        Route::get('', [TermController::class, 'index']);
        Route::post('', [TermController::class, 'store'])->name('-store');
        Route::patch('{term}', [TermController::class, 'update'])->name('-update');
        Route::delete('{term}', [TermController::class, 'destroy'])->name('-destroy');
    });

    Route::prefix('profile')->name('profile')->group(function () {
        Route::get('', [ProfileController::class, 'edit'])->name('.edit');
        Route::patch('', [ProfileController::class, 'update'])->name('.update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('.destroy');
    });
});

require __DIR__ . '/auth.php';
