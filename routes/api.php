<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\FeeController;
use App\Http\Controllers\Api\V1\TermController;
use App\Http\Controllers\Api\V1\GradeController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\RemarkController;
use App\Http\Controllers\Api\V1\VoteHeadController;
use App\Http\Controllers\Api\V1\StaffStatusController;
use App\Http\Controllers\Api\V1\FeeTransactionController;

Route::middleware('auth:api')->name('api')->prefix('api')->namespace('App\Http\Controllers')->group(function () {
    Route::name('-eschool')->prefix('eschool')->group(function () {

        Route::group(['as' => '-students', 'prefix' => 'students'], function () {
            Route::post('', ['as' => '', 'uses' => 'Api\StudentsController@getStudents']);
            Route::post('student', ['as' => '-student', 'uses' => 'Api\StudentsController@getStudent']);
            Route::post('status', ['as' => '-status', 'uses' => 'Api\StudentsController@setStatus']);
        });

        Route::group(['as' => '-staff', 'prefix' => 'staff'], function () {
            Route::post('status', ['as' => '-status', 'uses' => 'Api\V1\StaffController@status']);
        });

        Route::group(['as' => '-staff_status', 'prefix' => 'staff_status'], function () {
            Route::get('', [StaffStatusController::class, 'index'])->name('-list');
            Route::get('/{id}', [StaffStatusController::class, 'show'])->name('-show');
            Route::post('', [StaffStatusController::class, 'store'])->name('-store');
            Route::patch('', [StaffStatusController::class, 'update'])->name('-update');
            Route::delete('', [StaffStatusController::class, 'delete'])->name('-delete');
        });

        Route::group(['as' => '-grading', 'prefix' => 'grading'], function () {
            Route::group(['as' => '-remarks', 'prefix' => 'remarks'], function () {
                Route::get('', [RemarkController::class, 'index'])->name('-list');
                Route::get('/{id}', [RemarkController::class, 'show'])->name('-show');
                Route::post('', [RemarkController::class, 'store'])->name('-store');
                Route::patch('', [RemarkController::class, 'update'])->name('-update');
                Route::delete('', [RemarkController::class, 'delete'])->name('-delete');
            });
            Route::group(['as' => '-grades', 'prefix' => 'grades'], function () {
                Route::get('', [GradeController::class, 'index'])->name('-list');
                Route::get('/{id}', [GradeController::class, 'show'])->name('-show');
                Route::post('', [GradeController::class, 'store'])->name('-store');
                Route::patch('', [GradeController::class, 'update'])->name('-update');
                Route::delete('', [GradeController::class, 'delete'])->name('-delete');
            });
        });

        Route::group(['as' => '-intakes', 'prefix' => 'intakes'], function () {
            Route::post('', ['as' => '', 'uses' => 'IntakesController@fetchIntakes']);
            Route::post('subjects', ['as' => '-subjects', 'uses' => 'IntakesController@getSubjects']);
        });

        Route::group(['as' => '-departments', 'prefix' => 'departments'], function () {
            Route::post('courses', ['as' => '-courses', 'uses' => 'DepartmentsController@getCourses']);
            Route::post('intakes', ['as' => '-intakes', 'uses' => 'DepartmentsController@getIntakes']);
        });

        Route::group(['as' => '-courses', 'prefix' => 'courses'], function () {
            Route::post('intakes', ['as' => '-intakes', 'uses' => 'CoursesController@getIntakes']);
        });

        Route::group(['as' => '-stats', 'prefix' => 'stats'], function () {
            Route::post('totals', ['as' => '-totals', 'uses' => 'StudentsController@postTotals']);
            Route::post('enrolment', ['as' => '-enrolment', 'uses' => 'StudentsController@currentEnrolment']);
            Route::post('enrolment/status', ['as' => '-enrolment-status', 'uses' => 'StudentsController@overalEnrolmentStatus']);
            Route::post('enrolment/yearly', ['as' => '-enrolment-yearly', 'uses' => 'StudentsController@yearlyEnrolment']);
        });

        Route::prefix('fees')->name('-fees')->group(function () {
            Route::get('', [FeeController::class, 'index']);
            Route::get('show/{id}', [FeeController::class, 'show'])->name('-show');
            Route::post('', [FeeController::class, 'store'])->name('-store');
            Route::patch('', [FeeController::class, 'update'])->name('-update');
            Route::delete('', [FeeController::class, 'destroy'])->name('-delete');
            Route::prefix('transactions')->name('-transactions')->group(function () {
                Route::get('', [FeeTransactionController::class, 'index']);
                Route::get('show/{id}', [FeeTransactionController::class, 'show'])->name('-show');
                Route::post('', [FeeTransactionController::class, 'store'])->name('-store');
                Route::patch('', [FeeTransactionController::class, 'update'])->name('-update');
                Route::delete('', [FeeTransactionController::class, 'destroy'])->name('-delete');
            });
            Route::prefix('vote_heads')->name('-vote_heads')->group(function () {
                Route::get('', [VoteHeadController::class, 'index']);
                Route::get('show/{id}', [VoteHeadController::class, 'show'])->name('-show');
                Route::post('', [VoteHeadController::class, 'store'])->name('-store');
                Route::patch('', [VoteHeadController::class, 'update'])->name('-update');
                Route::delete('', [VoteHeadController::class, 'destroy'])->name('-delete');
            });
        });
        Route::prefix('terms')->name('-terms')->group(function () {
            Route::get('', [TermController::class, 'index']);
            Route::get('show/{id}', [TermController::class, 'show'])->name('-show');
            Route::post('', [TermController::class, 'store'])->name('-store');
            Route::patch('', [TermController::class, 'update'])->name('-update');
            Route::delete('', [TermController::class, 'destroy'])->name('-delete');
        });
        Route::prefix('courses')->name('-courses')->group(function () {
            Route::get('', [CourseController::class, 'index']);
            Route::get('show/{id}', [CourseController::class, 'show'])->name('-show');
            Route::post('', [CourseController::class, 'store'])->name('-store');
            Route::patch('', [CourseController::class, 'update'])->name('-update');
            Route::delete('', [CourseController::class, 'destroy'])->name('-delete');
        });
    });
});
