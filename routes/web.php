<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
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
    Route::get('/update_ac', function () {
        return Inertia::render('Academic/AcademicUpdate');
    })->name('actualizar_academico');

    Route::get('/look_ac', function () {
        return Inertia::render('Academic/AcademicSearch');
    })->name('buscar_academico');

    Route::get('/add_ac', function () {
        return Inertia::render('Academic/AcademicAdd');
    })->name('agregar_academico');

    // <!-- Administrative Search -->
    Route::get('/update_ad', function () {
        return Inertia::render('Administrative/AdministrativeUpdate');
    })->name('actualizar_administrativo');

    Route::get('/look_ad', function () {
        return Inertia::render('Administrative/AdministrativeSearch');
    })->name('buscar_administrativo');

    Route::get('/add_ad', function () {
        return Inertia::render('Administrative/AdministrativeAdd');
    })->name('agregar_administrativo');
});

require __DIR__ . '/auth.php';
