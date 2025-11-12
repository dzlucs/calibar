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
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index'); //ok

        //CRUD DOS DRINKS

        //CREATE
        Route::get('/admin/drinks/new', [DrinkController::class, 'new'])->name('drinks.new');//ok
        Route::post('/admin/drinks', [DrinkController::class, 'create'])->name('drinks.create');//ok
        Route::post('/admin/drinks/{drink_id}/images', [DrinkController::class, 'createDrinkImage'])->name('drinks.image.create');

        //READ
        Route::get('/admin/drinks', [DrinkController::class, 'index'])->name('drinks.index'); //ok
        Route::get('/admin/drinks/page/{page}', [DrinkController::class, 'index'])->name('drinks.paginate');
        Route::get('/admin/drinks/{drink_id}', [DrinkController::class, 'show'])->name('drinks.show');//ok

        //UPDATE
        Route::get('/admin/drinks/{drink_id}/edit', [DrinkController::class, 'edit'])->name('drinks.edit');//ok
        Route::put('/admin/drinks/{drink_id}', [DrinkController::class, 'update'])->name('drinks.update');//ok

        //DELETE
        Route::delete('/admin/drinks/{drink_id}', [DrinkController::class, 'destroy'])->name('drinks.destroy');//ok
    });

    Route::middleware('customer')->group(function () {
        Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    });
});
