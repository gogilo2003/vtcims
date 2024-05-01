<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\FeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V1\HomeController;
use App\Http\Controllers\V1\TermController;
use App\Http\Controllers\V1\StaffController;
use App\Http\Controllers\V1\CourseController;
use App\Http\Controllers\V1\IntakeController;
use App\Http\Controllers\V1\LessonController;
use App\Http\Controllers\V1\AccountController;
use App\Http\Controllers\V1\InvoiceController;
use App\Http\Controllers\V1\ProgramController;
use App\Http\Controllers\V1\SponsorController;
use App\Http\Controllers\V1\StudentController;
use App\Http\Controllers\V1\SubjectController;
use App\Http\Controllers\V1\EmployerController;
use App\Http\Controllers\V1\JobGroupController;
use App\Http\Controllers\V1\BogMemberController;
use App\Http\Controllers\V1\StaffRoleController;
use App\Http\Controllers\V1\AllocationController;
use App\Http\Controllers\V1\AttendanceController;
use App\Http\Controllers\V1\DepartmentController;
use App\Http\Controllers\V1\TranscriptController;
use App\Http\Controllers\V1\BogPositionController;
use App\Http\Controllers\V1\DesignationController;
use App\Http\Controllers\V1\ExaminationController;
use App\Http\Controllers\V1\StaffStatusController;
use App\Http\Controllers\V1\StudentRoleController;

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

    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::prefix('students')->name('students')->group(function () {
        Route::get('', [StudentController::class, 'index']);
        Route::post('', [StudentController::class, 'store'])->name('-store');
        Route::patch('{student}', [StudentController::class, 'update'])->name('-update');
        Route::delete('{student}', [StudentController::class, 'destroy'])->name('-destroy');
        Route::post('photo/{student}', [StudentController::class, 'picture'])->name('-photo');
        Route::get('download/{student?}', [StudentController::class, 'download'])->name('-download');
        Route::get('enrollment', [StudentController::class, 'enrollment'])->name('-enrollment');
        Route::prefix('roles')->name('-roles')->controller(StudentRoleController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store')->name('-store');
            Route::patch('{student_role}', 'update')->name('-update');
            Route::delete('{student_role}', 'destroy')->name('-destroy');
        });
    });

    Route::prefix('staff')->name('staff')->group(function () {
        Route::prefix('roles')->name('-roles')->group(function () {
            Route::get('', [StaffRoleController::class, 'index']);
            Route::post('', [StaffRoleController::class, 'store'])->name('-store');
            Route::patch('{staff_role}', [StaffRoleController::class, 'update'])->name('-update');
            Route::delete('{staff_role}', [StaffRoleController::class, 'destroy'])->name('-destroy');
        });
        Route::prefix('status')->name('-status')->group(function () {
            Route::get('', [StaffStatusController::class, 'index']);
            Route::post('', [StaffStatusController::class, 'store'])->name('-store');
            Route::patch('{staff_status}', [StaffStatusController::class, 'update'])->name('-update');
            Route::delete('{staff_status}', [StaffStatusController::class, 'destroy'])->name('-destroy');
        });
        Route::prefix('employers')->name('-employers')->group(function () {
            Route::get('', [EmployerController::class, 'index']);
            Route::post('', [EmployerController::class, 'store'])->name('-store');
            Route::patch('{employer}', [EmployerController::class, 'update'])->name('-update');
            Route::delete('{employer}', [EmployerController::class, 'destroy'])->name('-destroy');
        });
        Route::prefix('designations')->name('-designations')->group(function () {
            Route::get('', [DesignationController::class, 'index']);
            Route::post('', [DesignationController::class, 'store'])->name('-store');
            Route::patch('{designation}', [DesignationController::class, 'update'])->name('-update');
            Route::delete('{designation}', [DesignationController::class, 'destroy'])->name('-destroy');
        });
        Route::prefix('job_groups')->name('-job_groups')->group(function () {
            Route::get('', [JobGroupController::class, 'index']);
            Route::post('', [JobGroupController::class, 'store'])->name('-store');
            Route::patch('{job_group}', [JobGroupController::class, 'update'])->name('-update');
            Route::delete('{job_group}', [JobGroupController::class, 'destroy'])->name('-destroy');
        });
        Route::prefix('members')->name('-members')->group(function () {
            Route::get('', [StaffController::class, 'index']);
            Route::post('', [StaffController::class, 'store'])->name('-store');
            Route::patch('{staff}', [StaffController::class, 'update'])->name('-update');
            Route::delete('{staff}', [StaffController::class, 'destroy'])->name('-destroy');
            Route::post('photo/{staff}', [StaffController::class, 'picture'])->name('-photo');
            Route::get('download/{staff?}', [StaffController::class, 'download'])->name('-download');
        });
    });

    Route::prefix('bog')->name('bog')->group(function () {
        Route::prefix('positions')->name('-positions')->group(function () {
            Route::get('', [BogPositionController::class, 'index']);
            Route::post('', [BogPositionController::class, 'store'])->name('-store');
            Route::patch('{bog_position}', [BogPositionController::class, 'update'])->name('-update');
            Route::delete('{bog_position}', [BogPositionController::class, 'destroy'])->name('-destroy');
        });
        Route::prefix('members')->name('-members')->group(function () {
            Route::get('', [BogMemberController::class, 'index']);
            Route::post('', [BogMemberController::class, 'store'])->name('-store');
            Route::patch('{bog_member}', [BogMemberController::class, 'update'])->name('-update');
            Route::delete('{bog_member}', [BogMemberController::class, 'destroy'])->name('-destroy');
            Route::post('photo/{bog_member}', [BogMemberController::class, 'picture'])->name('-photo');
        });
    });

    Route::prefix('attendances')->name('attendances')->group(function () {
        Route::get('', [AttendanceController::class, 'index']);
        Route::get('download/{allocation}/excel', [AttendanceController::class, 'downloadExcel'])->name('-download-excel');
        Route::get('download/{allocation}/pdf', [AttendanceController::class, 'downloadPdf'])->name('-download-pdf');
        Route::get('show/{allocation_lesson}', [AttendanceController::class, 'showMark'])->name('-show-mark');
        Route::post('mark', [AttendanceController::class, 'mark'])->name('-mark');
        Route::post('upload', [AttendanceController::class, 'upload'])->name('-upload');
        Route::patch('{staff}', [AttendanceController::class, 'update'])->name('-update');
        Route::delete('{staff}', [AttendanceController::class, 'destroy'])->name('-destroy');
        Route::post('photo/{staff}', [AttendanceController::class, 'picture'])->name('-photo');
    });

    Route::prefix('lessons')->name('lessons')->group(function () {
        Route::get('', [LessonController::class, 'index']);
        Route::post('', [LessonController::class, 'store'])->name('-store');
        Route::patch('{lesson}', [LessonController::class, 'update'])->name('-update');
        Route::delete('{lesson}', [LessonController::class, 'destroy'])->name('-destroy');
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
        Route::patch('lessons/{allocation}', [AllocationController::class, 'lessons'])->name('-lessons');
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
    Route::prefix('examinations')->name('examinations')->group(function () {
        Route::get('', [ExaminationController::class, 'index']);
        Route::post('', [ExaminationController::class, 'store'])->name('-store');
        Route::patch('{examination}', [ExaminationController::class, 'update'])->name('-update');
        Route::delete('{examination}', [ExaminationController::class, 'destroy'])->name('-destroy');
        Route::get('show/{examination}', [ExaminationController::class, 'show'])->name('-show');
        Route::get('marklist/{examination}', [ExaminationController::class, 'marklist'])->name('-marklist');
        Route::prefix('transcripts')
            ->name('-transcripts')
            ->controller(TranscriptController::class)
            ->group(function () {
                Route::get('', 'index');
                Route::get('{term}/term', 'index')->name('-term');
                Route::get('{term}/department/{department}', 'index')->name('-department');
                Route::get('{term}/course/{course}', 'index')->name('-course');
                Route::get('{term}/intake/{intake}', 'index')->name('-intake');
                Route::get('{term}/student/{student}', 'index')->name('-student');
            });
    });

    Route::prefix('accounts')->name('accounts')->group(function () {
        Route::controller(AccountController::class)->group(function () {
            Route::get('', 'index');
        });
        Route::prefix('fees')->name('-fees')->controller(FeeController::class)->group(function () {
            Route::get('', 'index');
        });
        Route::prefix('invoices')->name('-invoices')->controller(InvoiceController::class)->group(function () {
            Route::get('', 'index');
        });
    });

    Route::prefix('profile')->name('profile')->group(function () {
        Route::get('', [ProfileController::class, 'edit'])->name('.edit');
        Route::patch('', [ProfileController::class, 'update'])->name('.update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('.destroy');
    });
});

require __DIR__ . '/auth.php';
