<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ExampleController;
use App\Http\Controllers\Admin\LocaleController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['web'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('examples', App\Http\Controllers\Admin\ExampleController::class);
        Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class);
        Route::resource('locales', App\Http\Controllers\Admin\LocaleController::class)->except(['show']);
        Route::post('locales/{locale}/set-default', [App\Http\Controllers\Admin\LocaleController::class, 'setDefault'])->name('locales.set-default');
        Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class);
    });

    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.store');
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
});
