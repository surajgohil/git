<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('login_view');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register_view', [App\Http\Controllers\registerController::class, 'register_view'])->name('register_view');
Route::get('/login_view', [App\Http\Controllers\registerController::class, 'login_view'])->name('login_view');

Route::post('/register_user', [App\Http\Controllers\registerController::class, 'register_user'])->name('register_user');
Route::post('/login_user', [App\Http\Controllers\registerController::class, 'login_user'])->name('login_user');


Route::post('/send_message', [App\Http\Controllers\chatController::class, 'send_message'])->name('send_message');
Route::post('/get_all_messages', [App\Http\Controllers\chatController::class, 'get_all_messages'])->name('get_all_messages');

Route::post('/invite_user', [App\Http\Controllers\chatController::class, 'invite_user'])->name('invite_user');





// Route::group(['middleware' => 'auth'], function () { 
    
// });