@extends('layouts.shop')

@section('title', 'Browse All Books — Margin')
@section('no-steps', '1')
@section('main-class', 'catalog-main')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('catalogFilterForm');
    const searchInput = document.getElementById('catalogSearchInput');

    if (form) {
        form.querySelectorAll('[data-auto-submit]').forEach(function (el) {
            el.addEventListener('change', function () {
                form.submit();
            });
        });
    }

    document.addEventListener('keydown', function (event) {
        if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            searchInput?.focus();
            searchInput?.select();
        }
    });
});
</script>
@endpush

@php
    $coverPresets = [
        'the-art-of-noticing' => ['theme' => 'cover-red', 'shape' => 'circle'],
        'a-field-guide-to-elsewhere' => ['theme' => 'cover-cream', 'shape' => 'steps'],
        'the-quiet-between' => ['theme' => 'cover-dark', 'shape' => 'split-circle'],
        'ways-of-seeing-a-city' => ['theme' => 'cover-cream', 'shape' => 'bars'],
        'in-praise-of-the-ordinary' => ['theme' => 'cover-dark', 'shape' => 'circle'],
        'a-wilder-kind-of-life' => ['theme' => 'cover-red', 'shape' => 'steps'],
        'letters-from-the-night' => ['theme' => 'cover-cream', 'shape' => 'split-circle'],
        'on-going-slowly' => ['theme' => 'cover-dark', 'shape' => 'bars'],
    ];

    $fallbackPresets = array_values($coverPresets);

    $buildSubjectUrl = function (?string $subjectKey) use ($search, $selectedFormats, $inStockOnly, $sort) {
        $params = [];
        if ($subjectKey !== null && $subjectKey !== '') {
            $params['subject'] = $subjectKey;
        }
        if ($search !== '') {
            $params['q'] = $search;
        }
        if (! empty($selectedFormats)) {
            $params['format'] = $selectedFormats;
        }
        if (! $inStockOnly) {
            $params['in_stock'] = '0';
        }
        if ($sort !== 'featured') {
            $params['sort'] = $sort;
        }

        return route('books.index', $params);
    };
@endphp

@section('content')
<div class="catalog-section">
    <form id="catalogFilterForm" method="GET" action="{{ route('books.index') }}">
        @if ($activeSubjectKey !== '')
            <input type="hidden" name="subject" value="{{ $activeSubjectKey }}">
        @endif

        <p class="eyebrow">THE COLLECTION / PUBLIC CATALOG</p>
        <h1 class="page-title">A shelf of possibilities.</h1>

        <div class="catalog-toolbar">
            <label class="catalog-search" for="catalogSearchInput">
                <span aria-hidden="true">&#128269;</span>
                <input
                    type="search"
                    id="catalogSearchInput"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search by title, author, or a little curiosity..."
                    aria-label="Search books"
                >
                <span class="shortcut" aria-hidden="true">&#8984; K</span>
            </label>

            <div class="catalog-sort">
                <select name="sort" aria-label="Sort books" data-auto-submit>
                    @foreach ($sortOptions as $value => $label)
                        <option value="{{ $value }}" @selected($sort === $value)>
                            Sort: {{ $label }}
                        </option>
                    @endforeach
                </select>
                <span class="chevron" aria-hidden="true">&#8744;</span>
            </div>
        </div>

        @guest
            <div class="catalog-guest-note">
                <p>Browse freely. Please log in or create an account before adding books and placing an order.</p>
                <a href="{{ route('login') }}">Log in / Register &#8599;</a>
            </div>
        @else
            <hr class="catalog-divider">
        @endguest

        <div class="catalog-layout">
            <aside class="catalog-sidebar" aria-label="Book filters">
                <h4>BROWSE BY SUBJECT</h4>
                <ul class="catalog-subject-list">
                    <li>
                        <a href="{{ $buildSubjectUrl(null) }}" class="{{ $activeSubjectKey === '' ? 'active' : '' }}">
                            <span class="subject-label">All books</span>
                            <span class="subject-count">{{ str_pad((string) $totalAllBooks, 2, '0', STR_PAD_LEFT) }}</span>
                        </a>
                    </li>
                    @foreach ($subjects as $subject)
                        <li>
                            <a href="{{ $buildSubjectUrl($subject['key']) }}" class="{{ $activeSubjectKey === $subject['key'] ? 'active' : '' }}">
                                <span class="subject-label">{{ $subject['label'] }}</span>
                                <span class="subject-count">{{ str_pad((string) $subject['count'], 2, '0', STR_PAD_LEFT) }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <hr>

                <h4>FORMAT</h4>
                <div class="catalog-filter-checks">
                    <label>
                        <input
                            type="checkbox"
                            name="format[]"
                            value="Paperback"
                            data-auto-submit
                            @checked(in_array('paperback', $selectedFormats, true))
                        >
                        <span>Paperback</span>
                    </label>
                    <label>
                        <input
                            type="checkbox"
                            name="format[]"
                            value="Hardcover"
                            data-auto-submit
                            @checked(in_array('hardcover', $selectedFormats, true))
                        >
                        <span>Hardcover</span>
                    </label>
                </div>

                <hr>

                <h4>AVAILABILITY</h4>
                <div class="catalog-filter-checks">
                    <input type="hidden" name="in_stock" value="0">
                    <label>
                        <input
                            type="checkbox"
                            name="in_stock"
                            value="1"
                            data-auto-submit
                            @checked($inStockOnly)
                        >
                        <span>In stock only</span>
                    </label>
                </div>
            </aside>

            <section aria-label="Books catalog">
                <div class="catalog-topbar">
                    <span>
                        {{ strtoupper($activeSubjectLabel) }} / {{ $books->total() }} {{ \Illuminate\Support\Str::plural('TITLE', $books->total()) }}
                    </span>
                    <span class="muted">THE OCTOBER EDIT</span>
                </div>

                @if ($books->isNotEmpty())
                    <div class="books-grid">
                        @foreach ($books as $index => $book)
                            @php
                                $preset = $coverPresets[$book->slug] ?? $fallbackPresets[$index % count($fallbackPresets)];
                                $themeClass = $preset['theme'];
                                $shape = $preset['shape'];
                            @endphp
                            <div class="book-card">
                                <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="book-cover {{ $themeClass }}">
                                    <div class="book-cover-top">{{ $book->publisher->name ?? 'M / EDITIONS' }}</div>
                                    <div class="book-cover-title">{{ $book->title }}</div>

                                    <div class="book-shape">
                                        @if ($shape === 'circle')
                                            <div class="shape-circle"></div>
                                        @elseif ($shape === 'steps')
                                            <div class="shape-steps">
                                                <div class="step1"></div>
                                                <div class="step2"></div>
                                                <div class="step3"></div>
                                            </div>
                                        @elseif ($shape === 'split-circle')
                                            <div class="shape-split-circle"></div>
                                        @else
                                            <div class="shape-bars">
                                                <div class="b1"></div>
                                                <div class="b2"></div>
                                                <div class="b3"></div>
                                                <div class="b4"></div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="book-cover-author">{{ $book->author?->full_name ?? 'Unknown author' }}</div>
                                </a>

                                <div class="book-meta">{{ $book->genre }}</div>
                                <h3 class="book-title">
                                    <a href="{{ route('books.show', ['book' => $book->slug]) }}">{{ $book->title }}</a>
                                </h3>

                                @php
                                    $rating = $bookRatings->get($book->id);
                                    $average = (float) ($rating?->average_rating ?? 0);
                                @endphp
                                <div class="catalog-rating">
                                    <span class="catalog-rating-stars" role="img" aria-label="{{ number_format($average, 1) }} out of 5 stars">
                                        <span aria-hidden="true">☆☆☆☆☆</span><span class="catalog-rating-fill" style="width: {{ max(0, min(100, $average / 5 * 100)) }}%" aria-hidden="true">★★★★★</span>
                                    </span>
                                    @if ($rating)
                                        <span>{{ number_format($average, 1) }} / 5 · {{ $rating->review_count }} {{ (int) $rating->review_count === 1 ? 'review' : 'reviews' }}</span>
                                    @else
                                        <span>No ratings yet</span>
                                    @endif
                                </div>
                                <div class="book-author-info">{{ $book->author?->full_name ?? 'Unknown author' }}</div>
                                <div class="book-format-stock">
                                    {{ $book->format ?? 'Paperback' }} / {{ $book->in_stock ? 'In stock' : 'Out of stock' }}
                                </div>

                                <div class="book-price-action">
                                    <div class="book-price">${{ number_format((float) $book->price, 2) }}</div>
                                    <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="view-book-btn">VIEW BOOK ↗</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="muted" style="padding: 40px 0; text-align: center;">
                        No books matched your filter. <a href="{{ route('books.index') }}" style="color: var(--accent);">Reset all filters</a>
                    </p>
                @endif

                <div class="catalog-foot">
                    <span>
                        @if ($books->total() > 0)
                            Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }} carefully chosen {{ \Illuminate\Support\Str::plural('book', $books->total()) }}
                        @else
                            Showing 0 of 0 carefully chosen books
                        @endif
                    </span>

                    <nav class="catalog-pages" aria-label="Pagination">
                        @if ($books->onFirstPage())
                            <span class="disabled" aria-disabled="true">&larr;</span>
                        @else
                            <a href="{{ $books->previousPageUrl() }}" aria-label="Previous page">&larr;</a>
                        @endif

                        @php
                            $lastPage = max(1, $books->lastPage());
                        @endphp
                        @for ($page = 1; $page <= $lastPage; $page++)
                            @if ($page === $books->currentPage())
                                <span class="active" aria-current="page">{{ str_pad((string) $page, 2, '0', STR_PAD_LEFT) }}</span>
                            @else
                                <a href="{{ $books->url($page) }}">{{ str_pad((string) $page, 2, '0', STR_PAD_LEFT) }}</a>
                            @endif
                        @endfor

                        @if ($books->hasMorePages())
                            <a href="{{ $books->nextPageUrl() }}" aria-label="Next page">&rarr;</a>
                        @else
                            <span class="disabled" aria-disabled="true">&rarr;</span>
                        @endif
                    </nav>
                </div>
            </section>
        </div>
    </form>
</div>
@endsection
