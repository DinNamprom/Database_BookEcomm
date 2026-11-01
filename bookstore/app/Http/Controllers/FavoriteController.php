<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Publisher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function page(Request $request): View
    {
        return view('member.favorites', $this->index($request)->getData(true));
    }

    public function wishlist(Request $request): View
    {
        $books = $request->user()->favoriteBooks()
            ->with(['author', 'publisher'])->orderBy('books.title')->get();

        return view('member.wishlist', ['books' => $books]);
    }

    public function index(Request $request): JsonResponse
    {
        $bookColumns = ['id', 'title', 'format', 'price', 'author_id', 'publisher_id'];
        $authors = $request->user()->favoriteAuthors()
            ->with(['books' => fn ($query) => $query->select($bookColumns)])
            ->orderBy('authors.first_name')->orderBy('authors.last_name')->get();
        $publishers = $request->user()->favoritePublishers()
            ->with(['books' => fn ($query) => $query->select($bookColumns)->with('author')])
            ->withCount('books')->orderBy('publishers.name')->get();

        return response()->json([
            'authors_count' => $authors->count(),
            'publishers_count' => $publishers->count(),
            'authors' => $authors->map(fn ($author) => [
                'id' => $author->id,
                'name' => $author->full_name,
                'books' => $author->books,
            ]),
            'publishers' => $publishers->map(fn ($publisher) => [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'books_count' => $publisher->books_count,
                'books' => $publisher->books,
            ]),
        ]);
    }

    public function saveBook(Request $request, Book $book): JsonResponse
    {
        $request->user()->favoriteBooks()->syncWithoutDetaching([$book->id]);

        return response()->json(['saved' => true]);
    }

    public function removeBook(Request $request, Book $book): JsonResponse
    {
        $request->user()->favoriteBooks()->detach($book->id);

        return response()->json(['saved' => false]);
    }

    public function saveAuthor(Request $request, Author $author): JsonResponse
    {
        $request->user()->favoriteAuthors()->syncWithoutDetaching([$author->id]);

        return response()->json(['saved' => true]);
    }

    public function removeAuthor(Request $request, Author $author): JsonResponse
    {
        $request->user()->favoriteAuthors()->detach($author->id);

        return response()->json(['saved' => false]);
    }

    public function savePublisher(Request $request, Publisher $publisher): JsonResponse
    {
        $request->user()->favoritePublishers()->syncWithoutDetaching([$publisher->id]);

        return response()->json(['saved' => true]);
    }

    public function removePublisher(Request $request, Publisher $publisher): JsonResponse
    {
        $request->user()->favoritePublishers()->detach($publisher->id);

        return response()->json(['saved' => false]);
    }
}