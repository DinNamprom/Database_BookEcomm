<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = CartItem::with('book.author')->where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $items->sum(fn ($item) => $item->quantity * $item->book->price);
        $shipping = 5.00;
        $tax = 0;
        $user = Auth::user();
        $address = $user->defaultAddress;

        return view('checkout.index', [
            'user'     => $user,
            'address'  => $address,
            'items'    => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax'      => $tax,
            'total'    => $subtotal + $shipping + $tax,
        ]);
    }

    public function store(Request $request)
    {
        // Placeholder checkout: card fields are validated only for "is this
        // field filled in" shape — they are NEVER checked against a real
        // card, charged, or stored anywhere.
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'max:30'],
            'expiry' => ['required', 'string', 'max:10'],
            'cvc' => ['required', 'string', 'max:6'],
            'agree' => ['accepted'],
        ]);

        $items = CartItem::with('book')->where('user_id', Auth::id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $items->sum(fn ($item) => $item->quantity * $item->book->price);
        $shippingCost = 5.00;
        $total = $subtotal + $shippingCost;

        $shipping = Shipping::create([
            'user_id' => Auth::id(),
            'buy_date' => now(),
            'ship_date' => null,
        ]);

        // shippings has no columns for address/line items, so we snapshot
        // the order into a one-time session flash for the confirmation page.
        session()->flash('order_summary', [
            'items' => $items->map(fn ($item) => [
                'title' => $item->book->title,
                'quantity' => $item->quantity,
                'price' => (float) $item->book->price,
            ])->all(),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'address' => "{$validated['street_address']}, {$validated['city']}, {$validated['state']} {$validated['zip_code']}",
        ]);

        CartItem::where('user_id', Auth::id())->delete();
        Cart::where('user_id', Auth::id())->update(['total_price' => 0]);

        return redirect()->route('checkout.confirmation', $shipping);
    }

    public function confirmation(Shipping $shipping)
    {
        abort_unless($shipping->user_id === Auth::id(), 403);

        return view('checkout.confirmation', [
            'shipping' => $shipping,
            'order' => session('order_summary'),
        ]);
    }
}