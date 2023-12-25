<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignCartOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //

        $sessionCart = session('cart', []);

        $user = $event->user;

        // Get or create the user's cart
        $userCart = $user->cart ?? Cart::create(['user_id' => $user->id]);

        foreach ($sessionCart as $item) {
            $existingCartItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $item["product_id"])
                ->first();
            if ($existingCartItem) {
                // Update the quantity if the product is already in the cart
                $existingCartItem->increment('quantity', $item['quantity']);
            } else {
                // dd("Here");
                // Create a new cart item if the product is not in the cart
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // // Clear the session cart after merging
        session(['cart' => []]);
    }
}
