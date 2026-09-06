<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ErpHeaderController;
use App\Http\Controllers\SelectModuleController;
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

Route::middleware('auth')->prefix('erp')->name('erp.')->group(function () {
    Route::get('/search', [ErpHeaderController::class, 'search'])->name('search');
    Route::post('/change-unit', [ErpHeaderController::class, 'changeUnit'])->name('change-unit');
    Route::post('/change-session', [ErpHeaderController::class, 'changeSessionYear'])->name('change-session');
});

Route::get('/home', function () {
    return redirect()->route('selectmodule');
})->middleware('auth')->name('home');
