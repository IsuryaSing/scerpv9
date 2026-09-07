<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ErpHeaderController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SelectModuleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/login/lookup-user', [LoginController::class, 'lookupUser'])->name('login.lookup-user');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/selectmodule', [SelectModuleController::class, 'index'])
    ->middleware('auth')
    ->name('selectmodule');

Route::get('/setActiveModul/{module}', [ModuleController::class, 'setActiveModule'])
    ->middleware('auth')
    ->name('module.activate');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->prefix('erp')->name('erp.')->group(function () {
    Route::get('/search', [ErpHeaderController::class, 'search'])->name('search');
    Route::post('/change-unit', [ErpHeaderController::class, 'changeUnit'])->name('change-unit');
    Route::post('/change-session', [ErpHeaderController::class, 'changeSessionYear'])->name('change-session');
});

Route::middleware('auth')->group(function () {
    Route::redirect('/master/subjects', '/subjects');

    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/form', [SubjectController::class, 'form'])->name('subjects.form');
    Route::get('/subjects/form/{class}', [SubjectController::class, 'form'])->name('subjects.form.class');
    Route::post('/subjects/save', [SubjectController::class, 'store'])->name('subjects.store');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
});

Route::get('/home', function () {
    return redirect()->route('selectmodule');
})->middleware('auth')->name('home');
