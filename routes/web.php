<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolesController;
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

Route::get('/', [HomeController::class,"index"]);
Route::get('/login', [HomeController::class,"logIn"]);

//--------------------------------------------- gestion des roles
Route::get('/roles/index', [RolesController::class,"index"]);
Route::post('/roles/save', [RolesController::class,"save"]);
