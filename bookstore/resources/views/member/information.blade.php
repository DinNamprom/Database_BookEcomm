@extends('layouts.shop')
@section('title', 'Member Information')
@section('body-class', 'member-page')
@section('main-class', 'member-main')
@section('no-steps', '1')

@push('scripts')
    @include('member._scripts')
@endpush

@section('hero')
    <section class="member-hero">
        <p class="eyebrow">YOUR MARGIN / MEMBER NO. M-{{ str_pad((string) auth()->id(), 5, '0', STR_PAD_LEFT) }}</p>
        <div class="hero-line"><h1>Hello, <span data-member-greeting>{{ auth()->user()->first_name ?: auth()->user()->name }}</span>.</h1><p>A space for your details, your orders, and whatever you're reading next.</p></div>
    </section>
@endsection

@section('content')
    <div class="account-grid">
        @include('member._sidebar')

        <div class="account-content">
            <div class="details-grid">
                <section aria-labelledby="personal-heading">
                    <h2 id="personal-heading">Personal details</h2>
                    <form data-member-form="profile" action="{{ route('member-information.update') }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="name-fields">
                            <div class="field"><label for="first_name">FIRST NAME</label><input id="first_name" name="first_name" autocomplete="given-name" maxlength="100" required value="{{ auth()->user()->first_name }}" aria-describedby="first_name-error"><p class="field-error" id="first_name-error" data-error="first_name"></p></div>
                            <div class="field"><label for="last_name">LAST NAME</label><input id="last_name" name="last_name" autocomplete="family-name" maxlength="100" required value="{{ auth()->user()->last_name }}" aria-describedby="last_name-error"><p class="field-error" id="last_name-error" data-error="last_name"></p></div>
                        </div>
                        <div class="field"><label for="email">EMAIL ADDRESS</label><input type="email" id="email" name="email" autocomplete="email" required value="{{ auth()->user()->email }}" aria-describedby="email-error"><p class="field-error" id="email-error" data-error="email"></p></div>
                        <div class="field"><label for="phone_number">PHONE NUMBER</label><input type="tel" id="phone_number" name="phone_number" autocomplete="tel" maxlength="20" value="{{ auth()->user()->phone_number }}" aria-describedby="phone_number-error"><p class="field-error" id="phone_number-error" data-error="phone_number"></p></div>
                        <div class="form-actions"><button class="primary-button" type="submit">Save changes <span>↗</span></button><a href="{{ route('security.edit') }}">Change password ↗</a></div>
                        <p class="form-status" role="status" aria-live="polite"></p>
                    </form>
                </section>

                <section id="delivery" class="delivery-card" aria-labelledby="delivery-heading">
                    <div class="card-heading"><h3 id="delivery-heading">DEFAULT DELIVERY</h3></div>
                    <p class="delivery-name" data-member-name>{{ auth()->user()->name }}</p>
                    <p class="delivery-address" data-delivery-address>{{ auth()->user()->address ?: 'No delivery address saved yet.' }}</p>
                    <form id="address-editor" data-member-form="address" action="{{ route('member-information.address.update') }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="field"><label for="address">DELIVERY ADDRESS</label><textarea id="address" name="address" autocomplete="street-address" rows="4" maxlength="2000" required aria-describedby="address-error">{{ auth()->user()->address }}</textarea><p class="field-error" id="address-error" data-error="address"></p></div>
                        <div class="form-actions"><button class="primary-button" type="submit">Save address ↗</button></div>
                        <p class="form-status" role="status" aria-live="polite"></p>
                    </form>
                    <p class="delivery-note">Your saved delivery address.</p>
                </section>
            </div>
        </div>
    </div>
@endsection