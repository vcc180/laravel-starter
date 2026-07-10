<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ExampleController;
use App\Http\Controllers\Admin\LocaleController;
use App\Http\Middleware\CheckPermission;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['web'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('examples', App\Http\Controllers\Admin\ExampleController::class)->middleware(CheckPermission::class.':example.*');
        Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class)->middleware(CheckPermission::class.':article.*');
        Route::resource('locales', App\Http\Controllers\Admin\LocaleController::class)->except(['show'])->middleware(CheckPermission::class.':locale.*');
        Route::post('locales/{locale}/set-default', [App\Http\Controllers\Admin\LocaleController::class, 'setDefault'])->name('locales.set-default')->middleware(CheckPermission::class.':locale.update');
        Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class)->middleware(CheckPermission::class.':permission.*');
        Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->middleware(CheckPermission::class.':role.*');
        Route::middleware(CheckPermission::class.':user.*')->group(function () {
            Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
            Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
            Route::get('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
            Route::put('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
            Route::delete('users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
            Route::put('users/{user}/roles', [App\Http\Controllers\Admin\UserRoleController::class, 'update'])->name('users.roles.update');
            Route::get('users/{user}/roles', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.roles.edit');
        });

        Route::middleware(CheckPermission::class.':repository.*')->group(function () {
            Route::get('repository', [App\Http\Controllers\Admin\RepositoryController::class, 'index'])->name('repository.index');
            Route::post('repository/{type}/{slug}/install', [App\Http\Controllers\Admin\RepositoryController::class, 'install'])->name('repository.install');
            Route::delete('repository/{type}/{slug}/uninstall', [App\Http\Controllers\Admin\RepositoryController::class, 'uninstall'])->name('repository.uninstall');
        });
    });

    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.store');
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
});

$moduleRoutePath = base_path('modules/blog/routes/web.php');
if (file_exists($moduleRoutePath)) {
    require $moduleRoutePath;
}

$publicModuleRoutePath = base_path('modules/blog/routes/public.php');
if (file_exists($publicModuleRoutePath)) {
    require $publicModuleRoutePath;
}

