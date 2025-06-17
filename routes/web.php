<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountMembershipsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/* Login page */
Route::get('/', fn () => view('login'))
    ->name('loginPage');
/* Register page */
Route::get('/register', fn () => view('register'))
    ->name('registerPage');
/* Login */
Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
/* Register */
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

/* Authenticated Middleware */
Route::middleware(['auth'])->group(function () {

    /* Logout */
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /* Dashboard */
    Route::get('/dashboard', fn () => view('dashboard'))
        ->name('dashboardPage');
    /* Create account */
    Route::post('/createAccount', [AccountController::class, 'create'])
        ->name('createAccount');
    /* Identity Middleware */
    Route::middleware(['identity'])->group(function () {
        /* Profile detail page */
        Route::get('/profileDetail/{user}', [UserController::class, 'index'])
            ->name('profileDetailPage');
        /* Edit profile details */
        Route::put('/editProfile/{user}', [UserController::class, 'edit'])
            ->name('editProfile');
    });
    /* Account members  Middleware */
    Route::middleware(['role'])->group(function () {
        /* Account detail page */
        Route::get('/accountDetail/{account}', [AccountController::class, 'index'])
            ->name('accountDetailPage');
        /* Leave account */
        Route::delete('/leaveAccount/{account}', [
            AccountMembershipsController::class,
            'leaveAccount',
        ])->name('leaveAccount')->middleware('role:member,moderator');
        /* Deposit money */
        Route::post('/depositMoney/{account}',
            [TransactionController::class, 'depositMoney'])
            ->name('depositMoney');
        /* Admin Middleware */
        Route::middleware(['role:admin'])->group(function () {
            /* Member management page */
            Route::get('/memberManagement/{account}',
                [AccountMembershipsController::class, 'index'])
                ->name('memberManagementPage');
            /* Edit account name */
            Route::put('/editAccountName/{account}',
                [AccountController::class, 'editName'])
                ->name('editAccountName');
            /* Delete account */
            Route::delete('/deleteAccount/{account}',
                [AccountController::class, 'deleteAccount'])
                ->name('deleteAccount');
            /* Change member role */
            Route::put('/changeMemberRole/{account}/{user}',
                [AccountMembershipsController::class, 'changeMemberRole'])
                ->name('changeMemberRole');
            /* Remove member from account */
            Route::delete('/removeMemberFromAccount/{account}/{user}', [
                AccountMembershipsController::class,
                'removeMember',
            ])->name('removeMemberFromAccount');

        });
        /* Admin, moderator Middleware */
        Route::middleware(['role:admin,moderator'])->group(function () {
            /* Add member to account */
            Route::post('/addMemberToAccount/{account}',
                [AccountMembershipsController::class, 'addMember'])
                ->name('addMemberToAccount');
            /* Make payment */
            Route::post('/sendPayment/{account}',
                [TransactionController::class, 'sendPayment'])
                ->name('sendPayment');
        });
    });

});
