@extends('layouts.shop')

@section('title', 'Log in | Margin')
@section('no-steps', '1')

@section('content')
    @include('auth._assets')

    <div class="auth-grid">
        <div>
            <p class="eyebrow" style="margin-top: 0;">The reader's entrance</p>
            <h1 class="auth-hero">Welcome back to the margins.</h1>
        </div>

        <div style="padding-top: 24px;">
            <h2 class="auth-title">Log in.</h2>

            @if (session('url.intended') || session('status'))
                <div class="notice" role="status">
                    <p class="notice-label">{{ session('status') ? 'Notice' : 'Before you order' }}</p>
                    <p>
                        {{ session('status') ?? "Please log in to add books to your bag and place an order. We'll bring you back to the collection." }}
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <input id="email" name="email" type="email" class="input @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="alex.morgan@email.com"
                           autocomplete="email" required autofocus>
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input id="password" name="password" type="password" class="input @error('password') is-invalid @enderror"
                               placeholder="Your password" autocomplete="current-password" required>
                        <button type="button" class="eye" data-toggle-password="password" aria-label="Show password">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="row-between">
                    <label class="check">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password? &#8599;</a>
                    @endif
                </div>

                <button type="submit" class="btn-block">Log in &amp; continue &nbsp; &#8599;</button>
                <p class="fine">A secure little corner of the internet, just for you.</p>
            </form>

            <hr class="rule" style="margin-top: 32px;">

            <h3 class="display" style="font-size: 30px; font-weight: 400; margin: 0 0 14px;">New around here?</h3>
            <p style="font-size: 12px; margin: 0 0 20px;">Create a free account to save your details and start your next chapter.</p>
            <a href="{{ route('register') }}" class="btn-block btn-outline">Become a member &nbsp; &#8599;</a>
        </div>
    </div>
@endsection