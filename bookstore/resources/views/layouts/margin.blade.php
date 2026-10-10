<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Margin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ink: #191919;
            --taupe: #68665F;
            --taupe-light: #BDB9AE;
            --cream: #F4F1E9;
            --accent: #D83A22;
        }

        /* Plain CSS on purpose: header lines and the two-column layout
           don't depend on Tailwind classes being compiled. */
        body {
            margin: 0;
            min-height: 100vh;
            background-color: var(--cream);
            color: var(--ink);
            font-family: 'IBM Plex Mono', ui-monospace, Menlo, Consolas, monospace;
            font-size: 13px;
        }
        h1, h2, .display { font-family: 'Bodoni Moda', serif; }
        a { color: inherit; text-decoration: none; }

        .wrap { max-width: none; margin: 0 auto; padding-left: 64px; padding-right: 64px; }

        /* Header */
        .topbar { background: var(--ink); color: var(--cream); font-size: 10px; letter-spacing: .1em; text-transform: uppercase; padding: 7px 0; }
        .site-header { border-bottom: 2px solid var(--ink); }
        .site-header .wrap { display: flex; align-items: center; justify-content: space-between; padding-top: 18px; padding-bottom: 18px; }
        .brand { font-family: 'Bodoni Moda', serif; font-size: 44px; line-height: 1; letter-spacing: .04em; }
        .brand sup { color: var(--accent); font-size: 11px; vertical-align: top; }
        .site-nav { display: flex; gap: 32px; font-size: 12px; }
        .site-user { display: flex; gap: 24px; align-items: center; font-size: 12px; }
        .site-user .bag { color: var(--accent); }
        /* Steps row */
        .steps { border-bottom: 1px solid var(--taupe-light); }
        .steps .wrap { display: flex; justify-content: space-between; align-items: center; padding-top: 16px; padding-bottom: 16px; font-size: 11px; color: var(--taupe); }
        .steps-list { display: flex; gap: 24px; }
        .signed-in { text-transform: uppercase; letter-spacing: .06em; color: var(--ink); }

        /* Page */
        .eyebrow { color: var(--accent); font-size: 11px; letter-spacing: .12em; text-transform: uppercase; margin: 36px 0 8px; }
        .page-title { font-size: 64px; line-height: 1.05; margin: 0 0 40px; font-weight: 400; }

        /* Two columns: content on the left, summary box on the right */
        .shop-grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 40px; align-items: start; }

        /* Cart table */
        .cart-table { width: 100%; border-collapse: collapse; border-top: 1px solid var(--ink); }
        .cart-table th { font-size: 10px; letter-spacing: .1em; text-transform: uppercase; font-weight: 400; padding: 12px 0; border-bottom: 1px solid var(--ink); text-align: left; }
        .cart-table td { padding: 24px 0; border-bottom: 1px solid var(--taupe-light); vertical-align: middle; }
        .cart-table .ctr { text-align: center; }
        .cart-table .num { text-align: right; }
        .cart-item { display: flex; gap: 20px; }
        .cover { flex: none; width: 64px; height: 88px; background: var(--taupe-light); display: flex; align-items: center; justify-content: center; text-align: center; font-size: 9px; padding: 4px; box-sizing: border-box; }
        .book-title { font-family: 'Bodoni Moda', serif; font-size: 22px; margin: 0 0 6px; }
        .muted { color: var(--taupe); font-size: 11px; }
        .link-remove { background: none; border: 0; padding: 0; margin-top: 6px; color: var(--accent); font: inherit; font-size: 11px; cursor: pointer; }
        .cart-foot { display: flex; justify-content: space-between; padding-top: 20px; font-size: 11px; color: var(--taupe); }

        /* Quantity stepper */
        .qty { display: inline-flex; align-items: center; border: 1px solid var(--ink); }
        .qty form { margin: 0; display: flex; }
        .qty button { background: none; border: 0; padding: 6px 10px; cursor: pointer; color: var(--ink); font: inherit; }
        .qty input { width: 36px; text-align: center; background: transparent; border: 0; font: inherit; color: var(--ink); padding: 0; -moz-appearance: textfield; }
        .qty input::-webkit-outer-spin-button, .qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

        /* Summary box */
        .summary { border: 1px solid var(--ink); padding: 24px; }
        .summary h2 { font-size: 30px; font-weight: 400; margin: 0 0 16px; padding-bottom: 16px; border-bottom: 1px solid var(--ink); }
        .summary .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 12px; }
        .summary .total { display: flex; justify-content: space-between; align-items: baseline; border-top: 1px solid var(--ink); margin-top: 12px; padding-top: 16px; }
        .summary .total .label { font-family: 'Bodoni Moda', serif; font-size: 26px; }
        .summary .total .amount { font-family: 'Bodoni Moda', serif; font-size: 40px; }
        .summary .divider { border-top: 1px solid var(--taupe-light); margin: 16px 0; }
        .btn-accent { display: block; text-align: center; background: var(--accent); color: #fff; padding: 14px; margin: 16px 0; font-size: 12px; }
        .summary .label-sm { font-size: 11px; letter-spacing: .06em; text-transform: uppercase; margin: 0 0 4px; }

        @media (max-width: 800px) {
            .shop-grid { grid-template-columns: minmax(0, 1fr); }
            .site-nav { display: none; }
            .page-title { font-size: 40px; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="wrap">Independent books. Uncommon perspectives.</div>
    </div>

    <header class="site-header">
        <div class="wrap">
            <a href="{{ url('/') }}" class="brand">MARGIN <sup>&reg;</sup></a>
            <nav class="site-nav">
                <a href="{{ url('/') }}">Home</a>
                <a href="#">Books</a>
                <a href="#">Our story</a>
            </nav>
            <div class="site-user">
                @auth
                    <span>{{ auth()->user()->name }} &#8599;</span>
                    <a href="{{ route('cart.index') }}" class="bag">Bag ({{ auth()->user()->cartItems->sum('quantity') }})</a>
                @endauth
                @guest
                    <span class="auth-links">
                        <a href="{{ route('login') }}">Log in</a> / <a href="{{ route('register') }}">Register</a>
                    </span>
                    <a href="#">Bag (0)</a>
                @endguest
            </div>
        </div>
    </header>

    <div class="steps">
        <div class="wrap">
            <div class="steps-list">
                <span>@yield('step-1', '01 / Bag')</span>
                <span>@yield('step-2', '02 / Checkout')</span>
                <span>@yield('step-3', '03 / Confirmation')</span>
            </div>
            @auth
                <span class="signed-in">&#10003; Signed in as {{ auth()->user()->name }}</span>
            @endauth
        </div>
    </div>

    <main class="wrap" style="padding-bottom: 64px;">
        @yield('content')
    </main>
       @include('partials.footer')
</body>
</html>