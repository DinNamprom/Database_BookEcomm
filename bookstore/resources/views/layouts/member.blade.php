<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — MARGIN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="member-page">
    <a class="skip-link" href="#main-content">Skip to content</a>
    <div class="announcement">INDEPENDENT BOOKS. UNCOMMON PERSPECTIVES.</div>
    <header class="member-header">
        <a href="{{ url('/') }}" class="wordmark" aria-label="Margin home">MARGIN<sup>®</sup></a>
        <nav aria-label="Main navigation"><a href="{{ url('/') }}">Home</a>@if(Route::has('books.index'))<a href="{{ route('books.index') }}">Books</a>@endif</nav>
        <a class="accent header-account" href="{{ route('member-information.index') }}"><span data-member-name>{{ auth()->user()->name }}</span> ↗</a>
    </header>
    <main id="main-content" class="member-main">
        @yield('hero')
        <div class="account-grid">
            <aside class="account-sidebar">
                <p class="sidebar-name" data-member-name>{{ auth()->user()->name }}</p>
                <nav aria-label="Account navigation">
                    <a href="{{ route('member-information.index') }}" @if(request()->routeIs('member-information.index')) aria-current="page" @endif>Profile & details @if(request()->routeIs('member-information.index')) → @endif</a>
                    @if(Route::has('orders.index'))<a href="{{ route('orders.index') }}">Order history</a>@endif
                    <a href="{{ route('member-information.index') }}#delivery">Saved address</a>
                    <a href="{{ route('favorites.index') }}" @if(request()->routeIs('favorites.index')) aria-current="page" @endif>Favorite @if(request()->routeIs('favorites.index')) → @endif</a>
                </nav>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-button">Log out ↗</button></form>
            </aside>
            <div class="account-content">@yield('content')</div>
        </div>
    </main>
    <footer class="member-footer"><div class="wordmark">MARGIN<sup>®</sup></div><p>For the curious. For the unhurried.<br>A little room for a different point of view.</p><small>© {{ date('Y') }} MARGIN BOOKS. READ OUTSIDE THE LINES.</small></footer>
</body>
</html>
