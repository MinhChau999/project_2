<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarriageController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckAdminMiddleware;
use App\Http\Middleware\CheckLoginMiddleware;
use App\Http\Middleware\CheckLogoutMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'processLogin'])->name('process_login');

Route::group([
    'middleware' => CheckLogoutMiddleware::class,
], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/forgot_password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
    Route::get('/reset_password/{token}', [AuthController::class, 'resetPassword'])->name('reset_password');
});

Route::group([
    'middleware' => CheckLoginMiddleware::class,
], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/index', [UserController::class, 'index'])->name('index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [UserController::class, 'show'])->name('profile');
    Route::post('/update/{user}', [UserController::class, 'updateProfile'])->name('update_profile');
    Route::post('/change_password', [UserController::class, 'changePassword'])->name('change_password');
});

Route::post('forgot_password', [AuthController::class, 'processForgotPassword'])->name('process_forgot_password');
Route::post('reset_password', [AuthController::class, 'processResetPassword'])->name('process_reset_password');

Route::group([
    'as' => 'users.',
    'prefix' => 'users',
    'middleware' => CheckAdminMiddleware::class,
], function () {
    Route::get('/', [UserController::class, 'show_users'])->name('show_users');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('destroy');
    Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('update');

    //    api
    Route::get('/api', [UserController::class, 'api'])->name('api');
    Route::get('/apiNameUsers', [UserController::class, 'apiNameUsers'])->name('api.name_users');
    Route::get('/apiProvinces', [UserController::class, 'apiProvinces'])->name('api.provinces');
});

Route::group([
    'as' => 'carriages.',
    'prefix' => 'carriages',
    'middleware' => CheckAdminMiddleware::class,
], function () {
    Route::get('/', [CarriageController::class, 'show_cars'])->name('show_cars');
    Route::get('/create', [CarriageController::class, 'create'])->name('create');
    Route::post('/store', [CarriageController::class, 'store'])->name('store');
    Route::delete('/destroy/{car}', [CarriageController::class, 'destroy'])->name('destroy');
    Route::get('/edit/{car}', [CarriageController::class, 'edit'])->name('edit');
    Route::post('/update{car}', [CarriageController::class, 'update'])->name('update');

    //    api
    Route::get('/api', [CarriageController::class, 'api'])->name('api');
});
