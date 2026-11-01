<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MemberInformationController;
use Illuminate\Support\Facades\Route;

// Session-authenticated data/actions for Favorite and Member Information.
Route::middleware('auth')->group(function () {
    Route::get('favorites', [FavoriteController::class, 'page'])->name('favorites.index');
    Route::get('wishlist', [FavoriteController::class, 'wishlist'])->name('wishlist.index');
    Route::view('member-information', 'member.information')->name('member-information.index');

    Route::get('favorites/data', [FavoriteController::class, 'index'])->name('favorites.data');
    Route::put('favorites/books/{book}', [FavoriteController::class, 'saveBook'])->name('favorites.books.save');
    Route::delete('favorites/books/{book}', [FavoriteController::class, 'removeBook'])->name('favorites.books.remove');
    Route::put('favorites/authors/{author}', [FavoriteController::class, 'saveAuthor'])->name('favorites.authors.save');
    Route::delete('favorites/authors/{author}', [FavoriteController::class, 'removeAuthor'])->name('favorites.authors.remove');
    Route::put('favorites/publishers/{publisher}', [FavoriteController::class, 'savePublisher'])->name('favorites.publishers.save');
    Route::delete('favorites/publishers/{publisher}', [FavoriteController::class, 'removePublisher'])->name('favorites.publishers.remove');

    Route::get('member-information/data', [MemberInformationController::class, 'show'])->name('member-information.data');
    Route::patch('member-information', [MemberInformationController::class, 'update'])->name('member-information.update');
    Route::patch('member-information/address', [MemberInformationController::class, 'updateAddress'])->name('member-information.address.update');
});
