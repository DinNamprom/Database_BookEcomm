<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/bag', [CartController::class, 'index'])->name('cart.index');
    Route::post('/bag/{book:id}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/bag/{book:id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/bag/{book:id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation/{shipping}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
});

Route::get('/books/{book:slug}/login', [BookController::class, 'beginPurchase'])
    ->name('books.purchase.login');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::post('/books/{book:slug}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')->name('reviews.store');

require __DIR__.'/settings.php';
require __DIR__.'/member-pages.php';
