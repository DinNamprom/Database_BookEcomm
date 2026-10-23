<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;

class BookController extends Controller
{
    public function show(Book $book)
    {
        $book->load(['author', 'publisher']);

        $relatedBooks = Book::with(['author', 'publisher'])
            ->where('id', '!=', $book->id)
            ->orderByDesc('publish_date')
            ->orderByDesc('id')
            ->take(2)
            ->get();

        $favoriteStates = [
            'book' => false,
            'author' => false,
            'publisher' => false,
        ];

        if ($user = auth()->user()) {
            $favoriteStates['book'] = $user->favoriteBooks()->whereKey($book->id)->exists();
            $favoriteStates['author'] = $book->author
                ? $user->favoriteAuthors()->whereKey($book->author->id)->exists()
                : false;
            $favoriteStates['publisher'] = $book->publisher
                ? $user->favoritePublishers()->whereKey($book->publisher->id)->exists()
                : false;
        }

        return view('books.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
            'favoriteStates' => $favoriteStates,
        ]);
    }

    public function beginPurchase(Book $book): RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('books.show', ['book' => $book->slug]);
        }

        request()->session()->put(
            'url.intended',
            route('books.show', ['book' => $book->slug]),
        );

        return redirect()->route('login');
    }
}
