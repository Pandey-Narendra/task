<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication
Auth::routes();

// Products
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});

// Carts
Route::middleware('auth')->group(function() {
    Route::resource('cart', CartController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
});
