<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


 
Route::get('interests', [InterestController::class, 'index']);
Route::post('store-interest', [InterestController::class, 'store']);
Route::post('edit-interest', [InterestController::class, 'edit']);
Route::post('delete-interest', [InterestController::class, 'destroy']);

Route::get('users', [UserController::class, 'index']);
Route::post('store-user', [UserController::class, 'store'])->name('store-user');
Route::post('edit-user', [UserController::class, 'edit']);
Route::post('delete-user', [UserController::class, 'destroy']);
