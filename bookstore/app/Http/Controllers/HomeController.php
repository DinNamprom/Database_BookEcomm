<?php

namespace App\Http\Controllers;

use App\Models\Book;

class HomeController extends Controller
{
    public function index()
    {
        $books = Book::with(['author', 'publisher'])
            ->where('in_stock', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('home', compact('books'));
    }
}
