<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class,"login"]);
Route::get('/login', [HomeController::class,"logIn"])->name('login');
Route::post('/singIn', [HomeController::class,"connexion"]);
Route::get('/logout', [HomeController::class,"logout"]);

//--------------------------------------------- gestion des roles


Route::post('/roles/getRoles', [RolesController::class,"getListRole"]);
Route::post('/roles/save', [RolesController::class,"save"]);
Route::get('/roles/list', [RolesController::class,"liste"]);
Route::get('/roles/edit/{id}', [RolesController::class,"edit"]);
Route::post('/roles/update', [RolesController::class,"update"]);


//--------------------------------------------- gestion des utilisateurs
Route::get('/users/index', [UserController::class,"index"]);
Route::post('/users/save', [UserController::class,"save"]);
Route::post('/users/get_users', [UserController::class,"getListUser"]);

//--------------------------------------------- Dasboard
Route::get('/dashboard/index', [DashboardController::class,"index"]);
