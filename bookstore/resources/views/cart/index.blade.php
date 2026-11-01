@extends('layouts.shop')
@section('title', 'Your Bag — Margin')
@section('step-1')<span style="color: var(--accent);">01 / Bag</span>@endsection

@php
    $bookCount = $items->sum('quantity');
    $from = now()->addDays(2);
    $to = now()->addDays(6);
    $arrives = $from->isSameMonth($to)
        ? $from->format('M j') . '-' . $to->format('j, Y')
        : $from->format('M j') . ' - ' . $to->format('M j, Y');
@endphp

@section('content')
<p class="eyebrow">Your next reads / {{ $bookCount }} {{ \Illuminate\Support\Str::plural('book', $bookCount) }}</p>
<h1 class="page-title">Good things in the bag.</h1>

<div class="shop-grid">
    <div>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Book</th>
                    <th class="ctr">Quantity</th>
                    <th class="num">Price</th>
                    <th class="num">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            <div class="cart-item">
                                <a href="{{ route('books.show', $item->book) }}" class="cover">{{ $item->book->title }}</a>
                                <div>
                                    <p class="book-title">
                                        <a href="{{ route('books.show', $item->book) }}">{{ $item->book->title }}</a>
                                    </p>
                                    <p style="margin: 0 0 6px;">{{ $item->book->author->full_name ?? '' }}</p>
                                    <p class="muted" style="margin: 0;">
                                        {{ $item->book->format }} / {{ $item->book->in_stock ? 'In stock' : 'Out of stock' }}
                                    </p>
                                    <form action="{{ route('cart.destroy', $item->book) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="link-remove">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td class="ctr">
                            <div class="qty">
                                <form action="{{ route('cart.update', $item->book) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                    <button type="submit" aria-label="Decrease quantity">&minus;</button>
                                </form>
                                <form action="{{ route('cart.update', $item->book) }}" method="POST" onchange="this.submit()">
                                    @csrf @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99">
                                </form>
                                <form action="{{ route('cart.update', $item->book) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ min(99, $item->quantity + 1) }}">
                                    <button type="submit" aria-label="Increase quantity">+</button>
                                </form>
                            </div>
                        </td>
                        <td class="num">${{ number_format($item->book->price, 2) }}</td>
                        <td class="num">${{ number_format($item->quantity * $item->book->price, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted" style="text-align: center; padding: 40px 0;">Your bag is empty.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="cart-foot">
            <a href="{{ url('/') }}">&larr; Keep browsing</a>
            <span>Quantities updated automatically</span>
        </div>
    </div>

    <aside class="summary">
        <h2>The short version.</h2>
        <div class="row"><span>Subtotal ({{ $bookCount }} {{ \Illuminate\Support\Str::plural('book', $bookCount) }})</span><span>${{ number_format($subtotal, 2) }}</span></div>
        <div class="row"><span>Standard shipping</span><span>${{ number_format($shipping, 2) }}</span></div>
        <div class="row"><span>Sales tax</span><span>${{ number_format($tax, 2) }}</span></div>
        <div class="total">
            <span class="label">Total</span>
            <span class="amount">${{ number_format($total, 2) }}</span>
        </div>
        <p class="muted" style="margin: 8px 0 0;">USD &middot; Shipping and tax based on your saved address.</p>

        @if ($items->isNotEmpty())
            <a href="{{ route('checkout.index') }}" class="btn-accent">Continue to checkout &nbsp;&#8599;</a>
        @endif

        <p class="muted" style="margin: 0;">Member checkout unlocked. You're logged in as {{ auth()->user()->name }}.</p>

        <div class="divider"></div>
        <p class="label-sm">Delivery</p>
        <p style="margin: 0; font-size: 12px;">Arrives {{ $arrives }}</p>

        <div class="divider"></div>
        <p class="muted" style="margin: 0;">Secure checkout / 30-day returns</p>
    </aside>
</div>
@endsection