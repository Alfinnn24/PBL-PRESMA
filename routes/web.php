<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// jangan lupa nanti dimodifikasi sesusai dengan kebutuhan, terus kasih comment kalau sekiranya butuh
Route::get('/', [WelcomeController::class, 'index']);

Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/register', function () {
    return view('auth.register');
});

