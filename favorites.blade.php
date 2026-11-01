@extends('layouts.shop')
@section('title', 'Favorite')
@section('body-class', 'member-page')
@section('main-class', 'member-main')
@section('no-steps', '1')

@push('scripts')
    @include('member._scripts')
@endpush

@section('content')
    @php
        // The existing favorites response contains IDs but omits slugs.
        $favoriteBookIds = collect($authors)->merge($publishers)
            ->flatMap(fn ($item) => $item['books'])->pluck('id')->unique();
        $favoriteBookSlugs = \App\Models\Book::query()
            ->whereIn('id', $favoriteBookIds)->pluck('slug', 'id');
    @endphp
    <div class="account-grid">
        @include('member._sidebar')

        <div class="account-content">
            <p class="favorites-status form-status" role="status" aria-live="polite"></p>
            @foreach(['authors' => ['title' => 'Fav Authors', 'items' => $authors], 'publishers' => ['title' => 'Fav Publishers', 'items' => $publishers]] as $kind => $section)
                <section class="favorite-section" data-favorite-section aria-labelledby="{{ $kind }}-heading">
                    <div class="section-heading"><h1 id="{{ $kind }}-heading">{{ $section['title'] }}</h1><span class="saved-count"><span data-favorite-count>{{ count($section['items']) }}</span> SAVED</span></div>
                    <p class="empty-state" data-empty-state @if(count($section['items'])) hidden @endif>No favorite {{ $kind }} saved yet.</p>
                    @foreach($section['items'] as $item)
                        <article class="favorite-row" data-favorite-row>
                            <div class="favorite-info">
                                <span class="eyebrow">{{ $kind === 'authors' ? 'AUTHOR' : 'PUBLISHER' }}</span>
                                <h2>{{ $item['name'] }}</h2>
                                @if($kind === 'publishers')<p class="catalog-count">{{ $item['books_count'] }} {{ $item['books_count'] === 1 ? 'title' : 'titles' }} in catalog</p>@endif
                                <div class="favorite-actions"><span class="accent">♥ Saved</span><button type="button" class="text-button" data-remove-favorite="{{ route('favorites.'.$kind.'.remove', $item['id']) }}" aria-label="Remove {{ $item['name'] }} from favorites">Remove ×</button></div>
                            </div>
                            <div class="favorite-books">
                                @forelse(array_slice($item['books'], 0, 2) as $book)
                                    <div class="favorite-book"><div class="book-cover" aria-hidden="true"><span>MARGIN / BOOKS</span><strong>{{ $book['title'] }}</strong><span>{{ $item['name'] }}</span></div><div><h3>{{ $book['title'] }}</h3><p>{{ $book['format'] }} / ฿{{ number_format((float) $book['price'], 2) }}</p>@if(Route::has('books.show') && $favoriteBookSlugs->get($book['id']))<a class="book-link" href="{{ route('books.show', ['book' => $favoriteBookSlugs->get($book['id'])]) }}">View book ↗</a>@endif</div></div>
                                @empty
                                    <p class="no-books">No books listed yet.</p>
                                @endforelse
                            </div>
                        </article>
                    @endforeach
                </section>
            @endforeach
        </div>
    </div>
@endsection