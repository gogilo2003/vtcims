<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V1\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('students')->name('students')->group(function () {
        Route::get('', [StudentController::class, 'index']);
        Route::post('', [StudentController::class, 'store'])->name('-store');
        Route::patch('{student}', [StudentController::class, 'update'])->name('-update');
        Route::delete('{student}', [StudentController::class, 'destroy'])->name('-destroy');
        Route::post('photo/{student}', [StudentController::class, 'picture'])->name('-photo');
    });

    Route::prefix('profile')->name('profile')->group(function () {
        Route::get('', [ProfileController::class, 'edit'])->name('.edit');
        Route::patch('', [ProfileController::class, 'update'])->name('.update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('.destroy');
    });
});

require __DIR__ . '/auth.php';
