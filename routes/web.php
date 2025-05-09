<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/* Pohyb po webu */
Route::get('/', fn () => view('login'))->name('loginPage');
Route::get('/register', fn () => view('register'))->name('registerPage');
Route::get('/dashboard', fn () => view('dashboard'))
    ->name('dashboardPage');
Route::get('/profileDetail/{user}', [UserController::class, 'index'])
    ->name('profileDetailPage');

/* Editace profilu */
Route::put('/editProfile/{user}', [UserController::class, 'edit'])
    ->name('editProfile');

/* Login&Register funkčnost */
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
