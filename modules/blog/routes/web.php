<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\CategoryController;
use Modules\Blog\Http\Controllers\PostController;
use Modules\Blog\Http\Controllers\TagController;
use App\Http\Middleware\CheckPermission;

Route::middleware(['web', 'auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware(CheckPermission::class.':blog.posts.*')->group(function () {
        Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
        Route::get('/blog/create', [PostController::class, 'create'])->name('blog.create');
        Route::post('/blog', [PostController::class, 'store'])->name('blog.store');
        Route::get('/blog/{post}', [PostController::class, 'show'])->name('blog.show');
        Route::get('/blog/{post}/edit', [PostController::class, 'edit'])->name('blog.edit');
        Route::put('/blog/{post}', [PostController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{post}', [PostController::class, 'destroy'])->name('blog.destroy');
    });

    Route::middleware(CheckPermission::class.':blog.categories.*')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::middleware(CheckPermission::class.':blog.tags.*')->group(function () {
        Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
        Route::get('/tags/create', [TagController::class, 'create'])->name('tags.create');
        Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
        Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
        Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
        Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    });
});
