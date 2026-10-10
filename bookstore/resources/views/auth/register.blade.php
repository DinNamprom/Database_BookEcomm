@extends('layouts.margin')

@section('title', 'Create an account | Margin')

@section('content')
    @include('auth._assets')

    <div class="auth-grid" style="padding-top: 48px;">
        {{-- Left: pitch --}}
        <div>
            <h1 class="auth-hero">You're in good company.</h1>
            <ul class="perks">
                <li>01 / A quicker checkout, every time</li>
                <li>02 / Your orders, all in one place</li>
                <li>03 / Reading notes, only if you want them</li>
            </ul>
        </div>

        {{-- Right: form --}}
        <div>
            <h2 class="auth-title" style="margin-bottom: 14px;">Create an account.</h2>
            <p class="stack-gap" style="margin: 0 0 8px; font-size: 12px;">
                Already a member? <a href="{{ route('login') }}" style="text-decoration: underline;">Log in &#8599;</a>
            </p>
            <p class="link-accent" style="margin: 0 0 24px; font-size: 11px;">An account is required to add books and place an order.</p>
            <hr class="rule rule-ink" style="margin: 0 0 28px;">

            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf

                <div class="field-row">
                    <div class="field">
                        <label for="first_name">First name *</label>
                        <input id="first_name" name="first_name" type="text" class="input @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name') }}" placeholder="Alex" autocomplete="given-name" required autofocus>
                        @error('first_name') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div class="field">
                        <label for="last_name">Last name *</label>
                        <input id="last_name" name="last_name" type="text" class="input @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name') }}" placeholder="Morgan" autocomplete="family-name" required>
                        @error('last_name') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="email">Email address *</label>
                    <input id="email" name="email" type="email" class="input @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="alex.morgan@email.com" autocomplete="email" required>
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="password">Password *</label>
                    <div class="input-wrap">
                        <input id="password" name="password" type="password" class="input @error('password') is-invalid @enderror"
                               placeholder="Create a password" autocomplete="new-password" required>
                        <button type="button" class="eye" data-toggle-password="password" aria-label="Show password">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @else
                        <p class="hint">Use at least 8 characters, including a number.</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm password *</label>
                    <div class="input-wrap">
                        <input id="password_confirmation" name="password_confirmation" type="password" class="input"
                               placeholder="Repeat your password" autocomplete="new-password" required>
                        <button type="button" class="eye" data-toggle-password="password_confirmation" aria-label="Show password">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <label class="check">
                    <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                    <span>I agree to the <a href="#">Terms of service</a> and <a href="#">Privacy policy</a>.</span>
                </label>
                @error('terms') <p class="error" style="margin: -6px 0 14px;">{{ $message }}</p> @enderror

                <label class="check" style="margin-bottom: 24px;">
                    <input type="checkbox" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }}>
                    <span>Send me reading notes and new arrivals. No noise, we promise.</span>
                </label>

                <button type="submit" class="btn-block">Create my account &nbsp; &#8599;</button>
                <p class="fine">* Required fields. Your personal details stay private.</p>
            </form>
        </div>
    </div>
@endsection