@extends('layouts.store')

@section('content')
<style>
    .home-section {
        padding: 3rem 2rem;
    }
    .section-header {
        margin-bottom: 3rem;
    }
    .section-subtitle {
        color: #d4462e;
        font-size: 0.9rem;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    .section-title-wrap {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }
    .section-title {
        font-size: 4rem;
        margin: 0;
        letter-spacing: -0.02em;
    }
    .browse-link {
        color: #1a1a1a;
        text-decoration: none;
        text-transform: uppercase;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .books-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    
    .book-card {
        display: flex;
        flex-direction: column;
    }
    
    .book-cover {
        aspect-ratio: 3/4;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        border: 1px solid #1a1a1a;
        margin-bottom: 1rem;
        position: relative;
        text-align: center;
    }
    
    .book-cover-top {
        position: absolute;
        top: 1rem;
        left: 1rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    
    .book-cover-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        line-height: 1.1;
        margin-bottom: 2rem;
    }
    
    .book-cover-author {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .book-shape {
        width: 120px;
        height: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .book-meta {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #666;
        margin-bottom: 0.5rem;
    }
    
    .book-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        margin: 0 0 1rem 0;
        line-height: 1.2;
    }
    
    .book-author-info {
        font-size: 0.9rem;
        margin-bottom: 0.2rem;
    }
    
    .book-format-stock {
        font-size: 0.8rem;
        color: #666;
        padding-bottom: 1rem;
        border-bottom: 1px solid #ccc;
        margin-bottom: 1rem;
    }
    
    .book-price-action {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .book-price {
        font-size: 1.1rem;
    }
    
    .view-book-btn {
        color: #d4462e;
        text-decoration: none;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }
    
    /* Shapes for dummy covers */
    .shape-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 10px solid white;
        position: relative;
    }
    .shape-circle::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background: white;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .shape-steps {
        width: 100px;
        height: 60px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .shape-steps .step1 { width: 100px; height: 5px; background: black; margin-bottom: 5px; }
    .shape-steps .step2 { width: 80px; height: 20px; border: 2px solid black; margin-bottom: 5px; }
    .shape-steps .step3 { width: 50px; height: 30px; background: black; }
    
    .shape-split-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: white;
        position: relative;
        overflow: hidden;
    }
    .shape-split-circle::after {
        content: '';
        position: absolute;
        top: 45%;
        left: -10px;
        right: -10px;
        height: 10px;
        background: #1a1a1a;
    }
    
    .shape-bars {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        height: 80px;
    }
    .shape-bars div { background: black; width: 20px; }
    .shape-bars .b1 { height: 40px; }
    .shape-bars .b2 { height: 70px; }
    .shape-bars .b3 { height: 50px; }
    .shape-bars .b4 { height: 80px; }

</style>

<div class="home-section">
    <div class="section-header">
        <div class="section-subtitle">ON OUR SHELVES / 001</div>
        <div class="section-title-wrap">
            <h2 class="section-title">Worth making room for.</h2>
            <a href="#" class="browse-link">Browse all books ↗</a>
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
                    <a href="#" class="view-book-btn">VIEW BOOK ↗</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
