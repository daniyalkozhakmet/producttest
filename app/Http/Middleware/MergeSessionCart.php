<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\CartItem;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MergeSessionCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is authenticated, merge the session cart with the user's cart

        if (auth()->guard('sanctum')->check()) {

            $this->mergeSessionCartWithUserCart(auth()->guard('sanctum')->user());
        }

        return $next($request);
    }
    private function mergeSessionCartWithUserCart($user)
    {
        $sessionCart = session('cart', []);

        // Get or create the user's cart
        $userCart = $user->cart ?? Cart::create(['user_id' => $user->id]);

        foreach ($sessionCart as $productId => $item) {
            $existingCartItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $productId)
                ->first();

            if ($existingCartItem) {
                // Update the quantity if the product is already in the cart
                $existingCartItem->increment('quantity', $item['quantity']);
            } else {
                // Create a new cart item if the product is not in the cart
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // Clear the session cart after merging
        session(['cart' => []]);
    }
}
