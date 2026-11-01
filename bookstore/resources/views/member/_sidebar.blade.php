<aside class="account-sidebar">
    <p class="sidebar-name">{{ auth()->user()->name }}</p>

    <nav aria-label="Account">
        <a href="{{ route('member-information.index') }}"
           @if (request()->routeIs('member-information.*')) aria-current="page" @endif>Member information</a>

        <a href="{{ route('favorites.index') }}"
           @if (request()->routeIs('favorites.*')) aria-current="page" @endif>Favorites</a>
        <a href="{{ route('wishlist.index') }}"
           @if (request()->routeIs('wishlist.*')) aria-current="page" @endif>Wishlist</a>
    </nav>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-button">Log out ↗</button>
    </form>
</aside>