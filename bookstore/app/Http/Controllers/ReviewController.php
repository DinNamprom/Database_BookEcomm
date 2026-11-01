<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'rating_star' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:5000'],
        ]);

        $now = now();

        DB::table('reviews')->insert([
            'book_id' => $book->id,
            'user_id' => $request->user()->id,
            'rating_star' => $validated['rating_star'],
            'comment' => $validated['comment'] ?? null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect(
            route('books.show', ['book' => $book->slug]).'#book-reviews'
        )->with('review_success', 'Your review has been submitted.');
    }
}
