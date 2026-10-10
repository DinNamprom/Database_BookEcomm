<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $items = CartItem::with('book.author')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $items->sum(fn ($item) => $item->quantity * $item->book->price);
        $shipping = $items->isEmpty() ? 0 : 5.00;
        $tax = 0; // placeholder, no real tax calculation

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $subtotal + $shipping + $tax,
        ]);
    }

    public function store(Request $request, Book $book)
    {
        $qty = $validated['quantity'] ?? 1;

        $query = CartItem::where('user_id', Auth::id())->where('book_id', $book->id);

        if ($query->exists()) {
            $query->increment('quantity', $qty);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => $qty,
            ]);
        }

        $this->refreshCartTotal();

        return redirect()->route('cart.index');
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        CartItem::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->update(['quantity' => $validated['quantity']]);

        $this->refreshCartTotal();

        return redirect()->route('cart.index');
    }

    public function destroy(Book $book)
    {
        CartItem::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->delete();

        $this->refreshCartTotal();

        return redirect()->route('cart.index');
    }

    private function refreshCartTotal(): void
    {
        $subtotal = CartItem::with('book')
            ->where('user_id', Auth::id())
            ->get()
            ->sum(fn ($item) => $item->quantity * $item->book->price);

        Cart::updateOrCreate(
            ['user_id' => Auth::id()],
            ['total_price' => $subtotal]
        );
    }
}