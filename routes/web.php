<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('', [AuthController::class, 'index'])->name('login');
Route::post('submit-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('submit-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 
Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard'); 
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('cerca_titolo', [AuthController::class, 'cerca_titolo'])->name('cerca_titolo');
Route::get('cerca_id', [AuthController::class, 'cerca_id'])->name('cerca_id');
Route::post('submit-cerca_titolo', [AuthController::class, 'postCercaTitolo'])->name('cerca_titolo.post'); 
Route::post('submit-cerca_id', [AuthController::class, 'postCercaId'])->name('cerca_id.post');
Route::post('submit-aggiungi_film', [AuthController::class, 'postAggiungiFilm'])->name('aggiungi_film.post'); 
Route::post('submit-elimina_film', [AuthController::class, 'postEliminaFilm'])->name('elimina_film.post');
