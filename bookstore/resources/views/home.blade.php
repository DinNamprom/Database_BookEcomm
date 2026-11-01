@extends('layouts.shop')

@section('title', 'Margin — Independent Books')
@section('no-steps', '1')
@section('main-class', 'home-main')

@section('content')
<div class="home-section">
    <div class="section-header">
        <div class="section-subtitle">ON OUR SHELVES / 001</div>
        <div class="section-title-wrap">
            <h2 class="section-title">Worth making room for.</h2>
            <a href="{{ route('books.index') }}" class="browse-link">Browse all books ↗</a>
        </div>
    </div>

    <div class="books-grid">
        @foreach($books as $index => $book)
            <div class="book-card">
                <div class="book-cover" style="background-color: #e0e0e0; color: black;">
                    <div class="book-cover-top">{{ $book->publisher->name ?? 'M / EDITIONS' }}</div>
                    <div class="book-cover-title">{{ $book->title }}</div>

                    <div class="book-shape">
                        @if($index == 0)
                            <div class="shape-circle"></div>
                        @elseif($index == 1)
                            <div class="shape-steps">
                                <div class="step1"></div>
                                <div class="step2"></div>
                                <div class="step3"></div>
                            </div>
                        @elseif($index == 2)
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

                    <div class="book-cover-author">{{ $book->author->full_name }}</div>
                </div>

                <div class="book-meta">{{ $book->genre }}</div>
                <h3 class="book-title">{{ $book->title }}</h3>

                <div class="book-author-info">{{ $book->author->full_name }}</div>
                <div class="book-format-stock">
                    {{ $book->format }} / {{ $book->in_stock ? 'In stock' : 'Out of stock' }}
                </div>

                <div class="book-price-action">
                    <div class="book-price">${{ number_format($book->price, 2) }}</div>
                    <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="view-book-btn">VIEW BOOK ↗</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection