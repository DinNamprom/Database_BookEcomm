<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $canonicalSubjects = [
            'fiction' => ['label' => 'Fiction', 'genre' => 'fiction'],
            'essays' => ['label' => 'Essays', 'genre' => 'essays'],
            'art-culture' => ['label' => 'Art & culture', 'genre' => 'art & culture'],
            'travel-place' => ['label' => 'Travel & place', 'genre' => 'travel & place'],
            'poetry' => ['label' => 'Poetry', 'genre' => 'poetry'],
            'nature' => ['label' => 'Nature', 'genre' => 'nature'],
        ];

        $search = trim((string) $request->query('q', ''));
        $subjectParam = trim((string) $request->query('subject', ''));
        $sort = (string) $request->query('sort', 'featured');

        $rawFormats = $request->query('format', []);
        if (is_string($rawFormats)) {
            $rawFormats = array_filter( array_map('trim', explode(',', $rawFormats)));
        } elseif (! is_array($rawFormats)) {
            $rawFormats = [];
        }
        $selectedFormats = collect($rawFormats)
            ->map(fn ($f) => strtolower(trim((string) $f)))
            ->filter(fn ($f) => in_array($f, ['paperback', 'hardcover'], true))
            ->unique()
            ->values()
            ->all();

        // Default to "In stock only" checked as shown in the reference design,
        // while allowing the user to uncheck it (which submits in_stock=0).
        $inStockParam = $request->query('in_stock');
        $inStockOnly = $inStockParam === null
            ? true
            : ! in_array((string) $inStockParam, ['0', 'false', 'all'], true);

        // Build base query (used for subject sidebar counts so counts reflect availability/format/search)
        $baseQuery = Book::query()->with(['author', 'publisher']);

        if ($inStockOnly) {
            $baseQuery->where('in_stock', '>', 0);
        }

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where('title', 'like', $like)
                    ->orWhere('genre', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('author', function ($aq) use ($like) {
                        $aq->where('first_name', 'like', $like)
                            ->orWhere('last_name', 'like', $like)
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$like]);
                    })
                    ->orWhereHas('publisher', function ($pq) use ($like) {
                        $pq->where('name', 'like', $like);
                    });
            });
        }

        if (! empty($selectedFormats)) {
            $baseQuery->where(function ($fq) use ($selectedFormats) {
                foreach ($selectedFormats as $format) {
                    $fq->orWhereRaw('LOWER(format) = ?', [$format]);
                }
            });
        }

        // Compute counts per genre from the base query
        $genreCounts = (clone $baseQuery)
            ->reorder()
            ->selectRaw('LOWER(genre) as genre_key, COUNT(*) as aggregate')
            ->whereNotNull('genre')
            ->groupByRaw('LOWER(genre)')
            ->pluck('aggregate', 'genre_key');

        $totalMatchingAllSubjects = (clone $baseQuery)->count();

        // Merge any additional genres present in the DB that are not in the canonical list
        $allDbGenres = Book::query()
            ->whereNotNull('genre')
            ->select('genre')
            ->distinct()
            ->pluck('genre');

        foreach ($allDbGenres as $dbGenre) {
            $slugKey = Str::slug($dbGenre);
            $lowerGenre = strtolower(trim($dbGenre));
            if ($slugKey !== '' && ! isset($canonicalSubjects[$slugKey])) {
                $canonicalSubjects[$slugKey] = [
                    'label' => Str::ucfirst(strtolower($dbGenre)),
                    'genre' => $lowerGenre,
                ];
            }
        }

        $subjects = [];
        foreach ($canonicalSubjects as $key => $meta) {
            $subjects[$key] = [
                'key' => $key,
                'label' => $meta['label'],
                'genre' => $meta['genre'],
                'count' => (int) ($genreCounts[$meta['genre']] ?? 0),
            ];
        }

        // Resolve active subject
        $activeSubjectKey = '';
        $activeSubjectLabel = 'All books';
        if ($subjectParam !== '' && strtolower($subjectParam) !== 'all') {
            $normalizedKey = Str::slug($subjectParam);
            $normalizedGenre = strtolower($subjectParam);

            if (isset($subjects[$normalizedKey])) {
                $activeSubjectKey = $normalizedKey;
                $activeSubjectLabel = $subjects[$normalizedKey]['label'];
                $baseQuery->whereRaw('LOWER(genre) = ?', [$subjects[$normalizedKey]['genre']]);
            } else {
                $activeSubjectKey = $normalizedKey;
                $activeSubjectLabel = $subjectParam;
                $baseQuery->whereRaw('LOWER(genre) = ?', [$normalizedGenre]);
            }
        }

        // Sorting
        $sortOptions = [
            'featured' => "Editor's picks",
            'price_asc' => 'Price: Low to High',
            'price_desc' => 'Price: High to Low',
            'newest' => 'Newest',
            'title_asc' => 'Title: A–Z',
        ];

        if (! array_key_exists($sort, $sortOptions)) {
            $sort = 'featured';
        }

        switch ($sort) {
            case 'price_asc':
                $baseQuery->orderBy('price', 'asc')->orderBy('id', 'asc');
                break;
            case 'price_desc':
                $baseQuery->orderBy('price', 'desc')->orderBy('id', 'asc');
                break;
            case 'newest':
                $baseQuery->orderByDesc('publish_date')->orderByDesc('id');
                break;
            case 'title_asc':
                $baseQuery->orderBy('title', 'asc')->orderBy('id', 'asc');
                break;
            case 'featured':
            default:
                $baseQuery->orderBy('id', 'asc');
                break;
        }

        $books = $baseQuery->paginate(8)->withQueryString();

        return view('books.index', [
            'books' => $books,
            'subjects' => $subjects,
            'totalAllBooks' => $totalMatchingAllSubjects,
            'activeSubjectKey' => $activeSubjectKey,
            'activeSubjectLabel' => $activeSubjectLabel,
            'selectedFormats' => $selectedFormats,
            'inStockOnly' => $inStockOnly,
            'search' => $search,
            'sort' => $sort,
            'sortOptions' => $sortOptions,
        ]);
    }

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
