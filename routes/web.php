<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DocumentAcademicController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\AdministrativeController;
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

Route::get('/login', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //<!-- Academic Search -->
    
    Route::get('/completeform_acad', function () {
        return Inertia::render('Academic/CompleteAcad');
    })->name('complete_registro_acad');

    Route::get('/update_ac', function () {
        return Inertia::render('Academic/AcademicUpdate');
    })->name('actualizar_academico');
    Route::post('/update_documents', [DocumentAcademicController::class, 'updateDocuments']);

    Route::get('/look_ac', function () {
        return Inertia::render('Academic/AcademicSearch');
    })->name('buscar_academico');
    Route::get('/acad_details', [DocumentAcademicController::class, 'getAcadDetails']);
    Route::get('/acad_download/{id}', [DocumentAcademicController::class, 'downloadAcadDocument']);


    Route::get('/add_ac', function () {
        return Inertia::render('Academic/AcademicAdd');
    })->name('agregar_academico');
    Route::post('/register_student', [DocumentAcademicController::class, 'registerStudent']);
    Route::post('/upload_documents', [DocumentAcademicController::class, 'uploadDocuments']);
    // <!-- Administrative Search -->

    Route::get('/completeform_ad', function () {
        return Inertia::render('Administrative/CompleteAdmin');
    })->name('complete_registro_ad');


    Route::get('/update_ad', function () {
        return Inertia::render('Administrative/AdministrativeUpdate');
    })->name('actualizar_administrativo');

    Route::get('/look_ad', function () {
        return Inertia::render('Administrative/AdministrativeSearch');
    })->name('buscar_administrativo');
    Route::get('/res_details', [AdministrativeController::class, 'getResDetails']);
    Route::get('/res_download/{id}', [AdministrativeController::class, 'downloadResDocument']);


    Route::get('/add_ad', function () {
        return Inertia::render('Administrative/AdministrativeAdd');
    })->name('agregar_administrativo');
    Route::post('/register_res', [AdministrativeController::class, 'registerRes']);
    Route::post('/upload_res', [AdministrativeController::class, 'uploadRes']);

    //Agregar Silabos

    Route::get('/completeform_syll', function () {
        return Inertia::render('Syllabus/CompleteSyll');
    })->name('complete_registro_syll');

    Route::get('/update_sb', function () {
        return Inertia::render('Syllabus/SyllabusUpdate');
    })->name('actualizar_silabo');

    Route::get('/look_sb', function () {
        return Inertia::render('Syllabus/SyllabusSearch');
    })->name('buscar_silabo');
    Route::get('/syllabus_details', [SyllabusController::class, 'getSyllabusDetails']);
    Route::get('/syllabus_download/{id}', [SyllabusController::class, 'downloadDocument']);

    Route::get('/add_sb', function () {
        return Inertia::render('Syllabus/SyllabusAdd');
    })->name('agregar_silabo');
    Route::post('/register_sb', [SyllabusController::class, 'registerSilabo']);
    Route::post('/upload_sb', [SyllabusController::class, 'uploadSyllabus']);
});

require __DIR__ . '/auth.php';
