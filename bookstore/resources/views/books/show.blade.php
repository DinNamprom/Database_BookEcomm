<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $book->title }} — M / Editions</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:opsz,wght@6..96,400&family=IBM+Plex+Mono:wght@400&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="{{ asset('css/book-detail.css') }}">
</head>

<body class="book-page">

    <main class="book-detail">

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

            <span class="book-detail__nav-back">
                ← Back to the collection
            </span>
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

                    <span>
                        {{ $book->format ?? 'Not specified' }}
                    </span>

                    <span class="book-detail__format-price">
                        ${{ number_format((float) $book->price, 2) }}
                        / Selected ✓
                    </span>

                </div>


                {{-- Quantity / Add to Bag --}}
                <div class="book-detail__purchase">

                    <div class="book-detail__quantity">

                        <span class="book-detail__quantity-button">
                            −
                        </span>

                        <span>1</span>

                        <span class="book-detail__quantity-button">
                            +
                        </span>

                    </div>

                    <div class="book-detail__add-button">

                        <span>
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

                        <span class="book-detail__login-link">
                            Log in / Register ↗
                        </span>

                        <span class="book-detail__save">
                            ♡ Save book · Log in
                        </span>

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

                    <span class="book-detail__related-link">
                        Browse all books ↗
                    </span>

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


        {{-- =====================================================
             NEWSLETTER
             ===================================================== --}}
        <section class="book-detail__newsletter">

            <div>

                <p class="book-detail__newsletter-label">
                    STAY IN THE LOOP
                </p>

                <h2 class="book-detail__newsletter-title">
                    New books, thoughtful notes.
                </h2>

            </div>

            <div class="book-detail__newsletter-form">

                <span class="book-detail__newsletter-placeholder">
                    Your email address
                </span>

                <span>
                    SUBSCRIBE ↗
                </span>

            </div>

        </section>

    </main>

</body>
</html>

