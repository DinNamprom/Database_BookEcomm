@extends('layouts.shop')
@section('title', 'Order Confirmed — Margin')
@section('step-3')<span style="color: var(--accent);">03 / Confirmation</span>@endsection

@section('content')
<div class="text-center py-16">
    <p class="text-xs tracking-widest mb-2" style="color: var(--accent);">THANK YOU</p>
    <h1 class="text-5xl mb-4">It's on its way.</h1>
    <p style="color: var(--taupe);">Order #{{ $shipping->id }} confirmed on {{ $shipping->buy_date->format('M j, Y') }}.</p>
</div>

@if ($order)
    <div style="max-width: 560px; margin: 0 auto; border: 1px solid var(--ink); padding: 24px;">
        <h2 class="text-2xl mb-4">Your order.</h2>
        @foreach ($order['items'] as $line)
            <div class="flex justify-between text-sm py-1">
                <span>{{ $line['title'] }} &times; {{ $line['quantity'] }}</span>
                <span>${{ number_format($line['quantity'] * $line['price'], 2) }}</span>
            </div>
        @endforeach
        <div class="border-t pt-3 mt-3 text-sm space-y-1" style="border-color: var(--taupe-light);">
            <div class="flex justify-between"><span>Subtotal</span><span>${{ number_format($order['subtotal'], 2) }}</span></div>
            <div class="flex justify-between"><span>Shipping</span><span>${{ number_format($order['shipping_cost'], 2) }}</span></div>
        </div>
        <div class="flex justify-between text-xl mt-3 mb-4"><span>Total</span><span>${{ number_format($order['total'], 2) }}</span></div>
        <p class="text-sm" style="color: var(--taupe);">Shipping to: {{ $order['address'] }}</p>
    </div>
@else
    <p class="text-center" style="color: var(--taupe);">Order details aren't available anymore on this page (refreshed too late), but your order was recorded.</p>
@endif

<div class="text-center mt-10">
    <a href="{{ url('/') }}" class="inline-block py-3 px-6" style="background-color: var(--accent); color: white;">Continue browsing</a>
</div>
@endsection