<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RemoveCartOnLogout
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
        $user = auth()->user();

        if ($user) {
            // Clear the user's cart
            $products = $user->cart->cartItems;

            foreach ($products as $product) {
                $this->addToSessionCart($product);
            }
            $user->cart->cartItems()->delete();
        }
    }
    public function addToSessionCart($product)
    {
        // Get the current cart items from the session or default to an empty array
        $cartItems = session()->get('cart', []);
        // Check if the product_id already exists in the cart
        $index = array_search($product->product_id, array_column($cartItems, 'product_id'));

        if ($index !== false) {
            // If the product_id exists, increment the quantity
            $cartItems[$index]['quantity'] += 1;
        } else {
            // If the product_id does not exist, add a new item to the cart
            $cartItems[] = [
                'product_id' => $product->product_id,
                'quantity' => $product->quantity,
            ];
        }

        // Store the updated cart items in the session
        session(['cart' => $cartItems]);

    }
}
