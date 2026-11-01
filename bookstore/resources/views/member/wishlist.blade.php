@extends('layouts.shop')
@section('title', 'Wishlist')
@section('body-class', 'member-page')
@section('main-class', 'member-main')
@section('no-steps', '1')

@push('scripts')
    @include('member._scripts')
@endpush

@section('content')
    <div class="account-grid">
        @include('member._sidebar')
        <div class="account-content">
            <p class="favorites-status form-status" role="status" aria-live="polite"></p>
            <section class="favorite-section" data-favorite-section aria-labelledby="wishlist-heading">
                <div class="section-heading"><h1 id="wishlist-heading">Wishlist</h1><span class="saved-count"><span data-favorite-count>{{ $books->count() }}</span> SAVED</span></div>
                <p class="empty-state" data-empty-state @if($books->isNotEmpty()) hidden @endif>No books saved yet. <a class="book-link" href="{{ route('books.index') }}">Browse books ↗</a></p>
                @foreach ($books as $book)
                    <article class="favorite-row" data-favorite-row>
                        <div class="favorite-info">
                            <span class="eyebrow">SAVED BOOK</span>
                            <h2>{{ $book->title }}</h2>
                            <p class="catalog-count">{{ $book->author?->full_name ?? 'Unknown author' }}</p>
                            <div class="favorite-actions"><span class="accent">♥ Saved</span><button type="button" class="text-button" data-remove-favorite="{{ route('favorites.books.remove', ['book' => $book->slug]) }}" aria-label="Remove {{ $book->title }} from wishlist">Remove ×</button></div>
                        </div>
                        <div class="favorite-books">
                            <div class="favorite-book">
                                <a class="book-cover" href="{{ route('books.show', ['book' => $book->slug]) }}" aria-label="View {{ $book->title }}"><span>MARGIN / BOOKS</span><strong>{{ $book->title }}</strong><span>{{ $book->author?->full_name ?? 'Unknown author' }}</span></a>
                                <div><h3>{{ $book->title }}</h3><p>{{ $book->format }} / ฿{{ number_format((float) $book->price, 2) }}</p><a class="book-link" href="{{ route('books.show', ['book' => $book->slug]) }}">View book ↗</a></div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>
        </div>
    </div>
@endsection
