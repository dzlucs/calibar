<?php

use App\Controllers\HomeController;
use Core\Router\Route;

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Controllers\User\DashboardController as UserDashboardController;

// Authentication
Route::get('/', [HomeController::class, 'index'])->name('root');

// Creating routes
Route::get('/layouts/login', [HomeController::class, 'login'])->name('layouts.login');



Route::get('/problems/{id}', [HomeController::class, 'show'])->name('problems.show');


Route::get('/problems/new', [HomeController::class, 'new'])->name('problems.new');
# Definir rota para create
Route::post('/problems', [HomeController::class, 'create'])->name('layouts.login');

Route::get('/problems/{id}/edit', [HomeController::class, 'edit'])->name('problems.edit');
# edição também vai ser uma rota get
Route::put('/problems/{id}', [HomeController::class, 'update'])->name('problems.update');

# por último, definir a rota delete
Route::delete('/problems/{id}', [HomeController::class, 'destroy'])->name('problems.destroy');
# Dessa forma, conseguimos ter o CRUD do nosso sistema


# Route::get('/problems',       [HomeController::class, 'index'])->name('problems.index');
# Route::get('/problems/new',   [HomeController::class, 'new'])->name('problems.new');
