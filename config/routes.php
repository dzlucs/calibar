<?php

use App\Controllers\AdminController;
use App\Controllers\HomeController;
use App\Controllers\AuthenticationsController;
use App\Controllers\CustomerController;
use App\Controllers\DrinkController;
use App\Models\Customer;
use Core\Router\Route;
use Core\Router\Router;

// Authentication
//Route::get('/', [HomeController::class, 'index'])->name('root');

Route::get('/', [AuthenticationsController::class, 'checkLogin'])->name('auth.check');
Route::get('/login', [AuthenticationsController::class, 'new'])->name('users.login');
Route::post('/login', [AuthenticationsController::class, 'authenticate'])->name('users.authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthenticationsController::class, 'destroy'])->name('users.logout');

    Route::middleware('admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

        //CRUD DOS DRINKS

        //CREATE
        Route::get('/admin/drinks/new', [DrinkController::class, 'new'])->name('drinks.new');
        Route::post('/admin/drinks', [DrinkController::class, 'create'])->name('drinks.create');

        //READ
        Route::get('/admin/drinks', [DrinkController::class, 'index'])->name('drinks.index');
        Route::get('/admin/drinks/{drink_id}', [DrinkController::class, 'show'])->name('drinks.show');

        //UPDATE
        Route::get('/admin/drinks/{drink_id}/edit', [DrinkController::class, 'edit'])->name('drinks.edit');
        Route::put('/admin/drinks/{drink_id}', [DrinkController::class, 'update'])->name('drinks.update');

        //DELETE
        Route::delete('/admin/drinks/{drink_id}', [DrinkController::class, 'destroy'])->name('drinks.destroy');
    });

    Route::middleware('customer')->group(function () {
        Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    });
});
