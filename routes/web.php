<?php

use App\Http\Controllers\SearchHistoryController;
use Illuminate\Support\Facades\Auth;
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
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('search');
    });
    Route::post('/search', [SearchHistoryController::class, 'store'])->name('search');
});


Route::post('/ajax-search', [SearchHistoryController::class, 'search'])->name('ajax-search');
Route::get('/history', [SearchHistoryController::class, 'index'])->name('history');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
