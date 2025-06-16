<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountMembershipsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/* Login&Register */
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');
Route::middleware(['auth'])->group(function () {
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
    Route::get('/profileDetail/{user}', [UserController::class, 'index'])
        ->name('profileDetailPage');
    Route::get('/accountDetail/{account}', [AccountController::class, 'index'])
        ->name('accountDetailPage');
    Route::get('/memberManagement/{account}',
        [AccountMembershipsController::class, 'index'])
        ->name('memberManagementPage');

    /* Editace profilu */
    Route::put('/editProfile/{user}', [UserController::class, 'edit'])
        ->name('editProfile');

    /* Vytvoření účtu */
    Route::post('/createAccount', [AccountController::class, 'create'])
        ->name('createAccount');
    /* Editace účtu */
    Route::put('/editAccountName/{account}',
        [AccountController::class, 'editName'])
        ->name('editAccountName');
    /* Smazání účtu */
    Route::delete('/deleteAccount/{account}',
        [AccountController::class, 'deleteAccount'])
        ->name('deleteAccount');
    /* Opustění účtu & smazání uživatele */
    Route::delete('/removeMemberFromAccount/{account}/{user?}', [
        AccountMembershipsController::class,
        'removeMember',
    ])
        ->name('removeMemberFromAccount');

    /* Změna role uživatele */
    Route::put('/changeMemberRole/{account}/{user}',
        [AccountMembershipsController::class, 'changeMemberRole'])
        ->name('changeMemberRole');

    /* Přidání uživatele do účtu */
    Route::post('/addMemberToAccount/{account}',
        [AccountMembershipsController::class, 'addMember'])
        ->name('addMemberToAccount');

    /* Vložení peněz */
    Route::post('/depositMoney/{account}',
        [TransactionController::class, 'depositMoney'])
        ->name('depositMoney');

    /* Platba */
    Route::post('/sendPayment/{account}',
        [TransactionController::class, 'sendPayment'])
        ->name('sendPayment');
});
