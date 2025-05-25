<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/* Logout */
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

/* Pohyb po webu */
Route::get('/', fn () => view('login'))
    ->name('loginPage');
Route::get('/register', fn () => view('register'))
    ->name('registerPage');
Route::get('/dashboard', fn () => view('dashboard'))
    ->name('dashboardPage');
Route::get('/editProfile/{user}', [UserController::class, 'index'])
    ->name('profileDetailPage');
Route::get('/accountDetail/{account}', [AccountController::class, 'index'])
    ->name('accountDetailPage');

/* Editace profilu */
Route::put('/editProfile/{user}', [UserController::class, 'edit'])
    ->name('editProfile');

/* Login&Register */
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

/* Vytvoření účtu */
Route::post('/createAccount', [AccountController::class, 'create'])
    ->name('createAccount');
