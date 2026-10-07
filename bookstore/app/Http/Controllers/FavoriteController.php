<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function page(Request $request): View
    {
        return view('member.favorites', $this->index($request)->getData(true));
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

    public function removeAuthor(Request $request, int $author): Response
    {
        $request->user()->favoriteAuthors()->detach($author);

        return response()->noContent();
    }

    public function removePublisher(Request $request, int $publisher): Response
    {
        $request->user()->favoritePublishers()->detach($publisher);

        return response()->noContent();
    }
}
