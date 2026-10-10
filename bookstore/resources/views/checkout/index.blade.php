@extends('layouts.shop')
@section('title', 'Checkout — Margin')
@section('step-2')<span style="color: var(--accent);">02 / Checkout</span>@endsection

@section('content')
<p class="text-xs tracking-widest mb-2" style="color: var(--accent);">THE LAST PAGE BEFORE A NEW CHAPTER</p>
<h1 class="text-5xl mb-8">Make them yours.</h1>

<style>
    .modal-backdrop { position: fixed; inset: 0; z-index: 50; background: rgba(25,25,25,.55); display: flex; align-items: center; justify-content: center; padding: 16px; }
    .modal-box { background: var(--cream); border: 1px solid var(--ink); border-top: 4px solid var(--accent); max-width: 420px; width: 100%; padding: 24px; box-shadow: 0 12px 40px rgba(0,0,0,.25); }
    .modal-box h2 { font-size: 24px; font-weight: 400; margin: 0 0 12px; }
    .modal-box ul { margin: 0 0 20px; padding-left: 18px; font-size: 12px; color: var(--accent); }
    .modal-box li { margin-bottom: 4px; }
    .modal-box button { background: var(--accent); color: #fff; border: 0; padding: 10px 24px; font: inherit; font-size: 12px; cursor: pointer; }
</style>

@if ($errors->any())
    <div id="error-modal" class="modal-backdrop" role="alertdialog" aria-modal="true" aria-labelledby="error-title">
        <div class="modal-box">
            <h2 id="error-title">Please check your details</h2>
            <ul>
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
            <button type="button" id="error-modal-close">OK</button>
        </div>
    </div>
@endif

<div class="shop-grid">
    <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="space-y-8">
        @csrf

        <div>
            <p class="text-xs" style="color: var(--accent);">01 / CONTACT</p>
            <p>{{ $user->email }}</p>
        </div>

        <div>
            <h2 class="text-2xl mb-4">02 / Where to?</h2>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="text-xs" style="color: var(--taupe);">FIRST NAME</label>
                    <input type="text" name="first_name" required value="{{ old('first_name', $user->name) }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div><label class="text-xs" style="color: var(--taupe);">LAST NAME</label>
                    <input type="text" name="last_name" required value="{{ old('last_name') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div class="col-span-2"><label class="text-xs" style="color: var(--taupe);">STREET ADDRESS</label>
                    <input type="text" name="street_address" required value="{{ old('street_address', $user->address) }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div><label class="text-xs" style="color: var(--taupe);">CITY</label>
                    <input type="text" name="city" required value="{{ old('city') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div><label class="text-xs" style="color: var(--taupe);">STATE</label>
                    <input type="text" name="state" required value="{{ old('state') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div><label class="text-xs" style="color: var(--taupe);">ZIP CODE</label>
                    <input type="text" name="zip_code" required value="{{ old('zip_code') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div><label class="text-xs" style="color: var(--taupe);">COUNTRY</label>
                    <input type="text" name="country" required value="{{ old('country', 'United States') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div class="col-span-2"><label class="text-xs" style="color: var(--taupe);">PHONE</label>
                    <input type="tel" name="phone" minlength="9" title="Enter 9 or 10 digits" value="{{ old('phone') }}" maxlength="10" inputmode="numeric" autocomplete="tel" placeholder="0812345678" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
            </div>
        </div>

        <div>
            <h2 class="text-2xl mb-4">03 / The journey</h2>
            <div class="border p-4 flex justify-between" style="border-color: var(--ink);">
                <div>
                    <p>Standard shipping</p>
                    <p class="text-xs" style="color: var(--taupe);">Tracked delivery</p>
                </div>
                <p>${{ number_format($shipping, 2) }}</p>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-2xl">04 / Payment</h2>
                <p class="text-xs" style="color: var(--taupe);">VISA / MASTERCARD / AMEX</p>
            </div>
            <p class="text-xs mb-4" style="color: var(--taupe);">Placeholder checkout — enter anything, nothing is charged or stored.</p>
            <div class="space-y-4">
                <div><label class="text-xs" style="color: var(--taupe);">CARDHOLDER NAME</label>
                    <input type="text" name="cardholder_name" required value="{{ old('cardholder_name') }}" autocomplete="cc-name" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2"><label class="text-xs" style="color: var(--taupe);">CARD NUMBER</label>
                        <input type="text" name="card_number" required minlength="19" title="Enter all 16 digits of your card number" maxlength="19" inputmode="numeric" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" value="{{ old('card_number') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                    <div><label class="text-xs" style="color: var(--taupe);">EXPIRY</label>
                        <input type="text" name="expiry" required pattern="(0[1-9]|1[0-2])\/[0-9]{2}" title="Use the format MM/YY, for example 12/28" maxlength="5" inputmode="numeric" autocomplete="cc-exp" placeholder="MM/YY" value="{{ old('expiry') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
                </div>
                <div class="w-1/3"><label class="text-xs" style="color: var(--taupe);">CVC</label>
                    <input type="text" name="cvc" required minlength="3" title="Enter the 3 or 4 digit security code" maxlength="4" inputmode="numeric" autocomplete="cc-csc" value="{{ old('cvc') }}" class="w-full border px-3 py-2" style="border-color: var(--taupe-light); background: transparent;"></div>
            </div>
        </div>
    </form>

    <div class="border p-6 h-fit" style="border-color: var(--ink);">
        <h2 class="text-2xl mb-4">Your order.</h2>
        @foreach ($items as $item)
            <div class="flex gap-3 mb-4">
                <div class="w-12 h-16 flex items-center justify-center text-[9px] text-center" style="background-color: var(--taupe-light);">{{ $item->book->title }}</div>
                <div class="flex-1 text-sm">
                    <p>{{ $item->book->title }}</p>
                    <p style="color: var(--taupe);">{{ $item->book->author->full_name ?? '' }}</p>
                    <p style="color: var(--taupe);">Qty {{ $item->quantity }} &times; ${{ number_format($item->book->price, 2) }}</p>
                </div>
                <p class="text-sm">${{ number_format($item->quantity * $item->book->price, 2) }}</p>
            </div>
        @endforeach
        <div class="border-t pt-3 mt-3 text-sm space-y-1" style="border-color: var(--taupe-light);">
            <div class="flex justify-between"><span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span></div>
            <div class="flex justify-between"><span>Standard shipping</span><span>${{ number_format($shipping, 2) }}</span></div>
            <div class="flex justify-between"><span>Sales tax</span><span>${{ number_format($tax, 2) }}</span></div>
        </div>
        <div class="flex justify-between text-xl mt-3 mb-4"><span>Total</span><span>${{ number_format($total, 2) }}</span></div>
        <label class="flex items-start gap-2 text-xs mb-4">
            <input type="checkbox" name="agree" value="1" form="checkout-form" required>
            I agree to the Terms of service and returns policy.
        </label>
        <button type="submit" form="checkout-form" class="w-full py-3" style="background-color: var(--accent); color: white;">
            Confirm order — ${{ number_format($total, 2) }}
        </button>
        <p class="text-xs mt-3" style="color: var(--taupe);">Placeholder checkout — your card is never actually charged.</p>
    </div>
</div>

<script>
    // Error popup: close with OK, Esc, or a click on the dark background.
    (function () {
        const modal = document.getElementById('error-modal');
        if (!modal) return;
        const close = () => modal.remove();
        document.getElementById('error-modal-close').addEventListener('click', close);
        modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
    })();

    // Auto-format checkout inputs: digits only, max length, and the "/" in the expiry date.
    (function () {
        const digitsOnly = (v) => v.replace(/\D/g, '');

        function bind(name, format) {
            const el = document.querySelector('[name="' + name + '"]');
            if (!el) return;
            el.addEventListener('input', (e) => {
                const deleting = !!e.inputType && e.inputType.startsWith('delete');
                el.value = format(el.value, deleting);
            });
            el.value = format(el.value, false); // also formats old() values after a validation error
        }

        // 1234 5678 1234 5678 (16 digits)
        bind('card_number', (v) => digitsOnly(v).slice(0, 16).replace(/(.{4})(?=.)/g, '$1 '));

        // MM/YY: the "/" is added after the month; typing 3-9 first becomes 03-09; month capped at 12
        bind('expiry', (v, deleting) => {
            let d = digitsOnly(v).slice(0, 4);
            if (d.length === 1 && d > '1') d = '0' + d;
            if (d.length >= 2 && +d.slice(0, 2) > 12) d = '12' + d.slice(2);
            if (d.length > 2) return d.slice(0, 2) + '/' + d.slice(2);
            if (d.length === 2 && !deleting) return d + '/';
            return d;
        });

        // 3 digits (Visa/Mastercard) or 4 digits (Amex)
        bind('cvc', (v) => digitsOnly(v).slice(0, 4));

        // 10 digits
        bind('phone', (v) => digitsOnly(v).slice(0, 10));
    })();
</script>
@endsection