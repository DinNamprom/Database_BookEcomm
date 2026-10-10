<?php

namespace App\Http\Controllers;

use App\Models\Book;

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

        return view('books.show', [
            'book' => $book,
            'relatedBooks' => $relatedBooks,
        ]);
    }
}