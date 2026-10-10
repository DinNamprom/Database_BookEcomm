@extends('layouts.shop')

@section('title', $book->title . ' — Margin')
@section('body-class', 'book-page')
@section('no-steps', '1')
@section('main-class', 'book-main')

@push('head')
    <link rel="stylesheet"
          href="{{ asset('css/book-detail.css') }}?v={{ filemtime(public_path('css/book-detail.css')) }}">
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveButton = document.getElementById('saveBookButton');

        if (!saveButton) return;

        const saveIcon = saveButton.querySelector('.book-detail__save-icon');
        const saveLabel = saveButton.querySelector('.book-detail__save-label');

        saveButton.addEventListener('click', function () {
            const isSaved = saveButton.classList.toggle('is-saved');

            if (isSaved) {
                saveIcon.textContent = '♥';
                saveLabel.textContent = 'Saved';
            } else {
                saveIcon.textContent = '♡';
                saveLabel.textContent = 'Save book · Log in';
            }
        });
    });
</script>
@endpush

@section('content')

<div class="book-detail">

    {{-- =====================================================
         TOP NAVIGATION
         ===================================================== --}}
    <nav class="book-detail__nav">
        <span class="book-detail__nav-left">
            Books
            @if ($book->genre)
                / {{ $book->genre }}
            @endif
            / {{ $book->title }}
        </span>

        <button type="button" class="book-detail__back" id="backToCollection">
            ← Back to the collection
        </button>
    </nav>


    {{-- =====================================================
         HERO / BOOK INFORMATION
         ===================================================== --}}
    <section class="book-detail__hero">

        {{-- -------------------------------------------------
             BOOK COVER
             ------------------------------------------------- --}}
        <div class="book-detail__cover-column">

            <div class="book-detail__cover-frame">

                <div class="book-detail__cover">

                    <p class="book-detail__cover-brand">
                        M / EDITIONS
                    </p>

                    <h1 class="book-detail__cover-title">
                        {{ $book->title }}
                    </h1>

                    {{-- Presentation-only cover artwork.
                         There is currently no cover_image field
                         in the books table. --}}
                    <div class="book-detail__cover-graphic">

                        <img
                            src="{{ asset('assets/book-detail/observation-circle.svg') }}"
                            alt=""
                            class="book-detail__cover-graphic-circle"
                        >

                        <img
                            src="{{ asset('assets/book-detail/focal-point.svg') }}"
                            alt=""
                            class="book-detail__cover-graphic-point"
                        >

                    </div>

                    <p class="book-detail__cover-author">
                        {{ $book->author?->full_name ?? 'Unknown author' }}
                    </p>

                </div>

            </div>

            <div class="book-detail__cover-caption">
                <span>M / EDITIONS</span>
                <span>
                    {{ strtoupper($book->format ?? 'BOOK') }} / FRONT COVER
                </span>
            </div>

        </div>


        {{-- -------------------------------------------------
             BOOK INFORMATION
             ------------------------------------------------- --}}
        <div class="book-detail__info">

            <p class="book-detail__category">
                {{ strtoupper($book->genre ?? 'BOOK') }}
            </p>

            <h2 class="book-detail__title">
                {{ $book->title }}
            </h2>


            {{-- Author / Publisher --}}
            <div class="book-detail__metadata">

                <p>
                    By {{ $book->author?->full_name ?? 'Unknown author' }} ↗
                </p>

                <p class="book-detail__metadata-publisher">
                    Published by
                    {{ $book->publisher?->name ?? 'Unknown publisher' }}
                    ↗
                </p>

            </div>


            <div class="book-detail__divider book-detail__info-divider"></div>


            {{-- Price / Stock --}}
            <div class="book-detail__price-row">

                <p class="book-detail__price">
                    ${{ number_format((float) $book->price, 2) }}
                </p>

                @if ($book->in_stock > 0)

                    <p class="book-detail__stock book-detail__stock--available">
                        ● IN STOCK
                    </p>

                @else

                    <p class="book-detail__stock book-detail__stock--unavailable">
                        ● OUT OF STOCK
                    </p>

                @endif

            </div>


            {{-- Description --}}
            <p class="book-detail__description">
                {{ $book->description ?? 'No description available.' }}
            </p>


            {{-- Format --}}
            <div class="book-detail__format">
                <span class="book-detail__format-name">
                    {{ $book->format ?? 'Not specified' }}
                </span>

                <span class="book-detail__format-price">
                    ${{ number_format((float) $book->price, 2) }}
                    <span class="book-detail__selected">
                        / Selected ✓
                    </span>
                </span>
            </div>


            {{-- Quantity + Add to Bag --}}
            <div class="book-detail__purchase">
                <div class="book-detail__quantity">
                    <span class="book-detail__quantity-button">
                        −
                    </span>

                    <span class="book-detail__quantity-value">
                        1
                    </span>

                    <span class="book-detail__quantity-button">
                        +
                    </span>
                </div>

                <div class="book-detail__add-button">
                    <span class="book-detail__add-label">
                        Log in to add to bag
                    </span>

                    <span class="book-detail__add-arrow">
                        ↗
                    </span>
                </div>
            </div>


            {{-- Login / Save message --}}
            <div class="book-detail__login-note">
                <p>
                    Please log in or create an account before adding books
                    and placing an order. Your selection will be here.
                </p>

                <div class="book-detail__login-links">
                    <button type="button" class="book-detail__login-link" id="loginButton">
                        Log in / Register ↗
                    </button>

                    <button type="button" class="book-detail__save" id="saveBookButton">
                        <span class="book-detail__save-icon">♡</span>
                        <span class="book-detail__save-label">Save book · Log in</span>
                    </button>
                </div>
            </div>


            <div class="book-detail__divider book-detail__info-divider"></div>

        </div>

    </section>


    {{-- =====================================================
         DIVIDER
         ===================================================== --}}
    <div class="book-detail__divider book-detail__hero-bottom book-detail__divider--dark"></div>


    {{-- =====================================================
         DESCRIPTION / PARTICULARS
         ===================================================== --}}
    <section class="book-detail__particulars">

        {{-- -------------------------------------------------
             DESCRIPTION
             ------------------------------------------------- --}}
        <div class="book-detail__description-column">

            <p>
                {{ $book->description ?? 'No description available.' }}
            </p>

            @if ($book->description)
                <p>
                    Discover more about this book and explore the
                    details that make it part of the collection.
                </p>
            @endif

        </div>


        {{-- -------------------------------------------------
             PARTICULARS
             ------------------------------------------------- --}}
        <div>

            <h2 class="book-detail__particulars-title">
                The particulars.
            </h2>

            <dl class="book-detail__particulars-list">

                {{-- Publisher --}}
                <div class="book-detail__particular">

                    <dt>
                        Publisher
                    </dt>

                    <dd>
                        {{ $book->publisher?->name ?? 'Not specified' }}
                    </dd>

                </div>


                {{-- Format --}}
                <div class="book-detail__particular">

                    <dt>
                        Format
                    </dt>

                    <dd>
                        {{ $book->format ?? 'Not specified' }}
                    </dd>

                </div>


                {{-- Publication Year --}}
                <div class="book-detail__particular">

                    <dt>
                        Publication year
                    </dt>

                    <dd>
                        {{ $book->publish_date?->format('Y') ?? 'Not specified' }}
                    </dd>

                </div>


                {{-- Genre --}}
                <div class="book-detail__particular">

                    <dt>
                        Genre
                    </dt>

                    <dd>
                        {{ $book->genre ?? 'Not specified' }}
                    </dd>

                </div>

            </dl>

        </div>

    </section>


    {{-- =====================================================
         DIVIDER
         ===================================================== --}}
    <div class="book-detail__divider book-detail__hero-bottom book-detail__divider--dark"></div>


    {{-- =====================================================
         RELATED BOOKS
         ===================================================== --}}
    @if ($relatedBooks->isNotEmpty())

        <section class="book-detail__related">

            <div class="book-detail__related-header">

                <h2 class="book-detail__related-title">
                    Keep following your curiosity.
                </h2>

                <button type="button" class="book-detail__related-link" id="browseAllBooks">
                    Browse all books ↗
                </button>

            </div>


            <div class="book-detail__related-grid">

                @foreach ($relatedBooks as $relatedBook)

                    <article class="book-detail__related-card">

                        {{-- ---------------------------------
                             Related Book Cover
                             --------------------------------- --}}
                        <div class="book-detail__related-cover">

                            <p class="book-detail__related-cover-brand">
                                M / EDITIONS
                            </p>

                            <h3 class="book-detail__related-cover-title">
                                {{ $relatedBook->title }}
                            </h3>

                            <div class="book-detail__related-cover-bars">

                                <i class="book-detail__related-cover-bar"></i>
                                <i class="book-detail__related-cover-bar"></i>
                                <i class="book-detail__related-cover-bar"></i>
                                <i class="book-detail__related-cover-bar"></i>

                            </div>

                            <p class="book-detail__related-cover-author">
                                {{ $relatedBook->author?->full_name ?? 'UNKNOWN AUTHOR' }}
                            </p>

                        </div>


                        {{-- ---------------------------------
                             Related Book Information
                             --------------------------------- --}}
                        <div class="book-detail__related-content">

                            <p class="book-detail__related-category">
                                {{ strtoupper($relatedBook->genre ?? 'BOOK') }}
                            </p>

                            <h3 class="book-detail__related-book-title">
                                {{ $relatedBook->title }}
                            </h3>

                            <p class="book-detail__related-author">
                                {{ $relatedBook->author?->full_name ?? 'Unknown author' }}
                            </p>

                            <p class="book-detail__related-meta">

                                {{ $relatedBook->format ?? 'Book' }}

                                /

                                @if ($relatedBook->in_stock > 0)
                                    In stock
                                @else
                                    Out of stock
                                @endif

                            </p>


                            <div class="book-detail__related-footer">

                                <span>
                                    ${{ number_format((float) $relatedBook->price, 2) }}
                                </span>

                                <a
                                    href="{{ route('books.show', ['book' => $relatedBook->slug]) }}"
                                    class="book-detail__related-view"
                                >
                                    VIEW BOOK ↗
                                </a>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

        </section>

    @endif

</div>

@endsection