<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    // app/Http/Controllers/CartController.php
    use HttpResponses;
    public function get_my_cart()
    {
        try {
            $user = auth()->guard("sanctum")->user();
            if (!$user) {
                return response()->json(['message' => 'Cart is empty.'], 404);
            }
            $cartItems = $user->cart->cartItems()->with('product')->get();
            return $cartItems;
        } catch (\Exception $exception) {
            //throw $th;
            return $this->error('', $exception->getMessage(), 404);
        }
    }
    public function add_to_cart($productId)
    {
        try {
            //code...
            $product = Product::find($productId);
            if (!$product) {
                return response()->json(['error' => 'Product not found.'], 404);
            }

            // Check if the user is authenticated
            if (auth()->guard("sanctum")->check()) {

                $this->addToUserCart(auth()->guard("sanctum")->user(), $product);
            } else {
                // If the user is not authenticated, store the product in the session
                $val = $this->addToSessionCart($product);
                return response()->json(['message' => $val]);
            }

            return response()->json(['message' => 'Product added to cart successfully.']);
        } catch (\Exception $exception) {
            //throw $th;
            return $this->error('', $exception->getMessage(), 404);
        }
    }

    // Helper method to add the product to the user's cart
    private function addToUserCart($user, $product)
    {
        try {
            $userCart = $user->cart;
            // Create a new cart if the user doesn't have one
            if (!$userCart) {
                $userCart = Cart::create(['user_id' => $user->id]);
            }

            // Check if the product is already in the user's cart
            $existingCartItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingCartItem) {
                // Update the quantity if the product is already in the cart
                $existingCartItem->increment('quantity');
            } else {
                // Create a new cart item if the product is not in the cart
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
        } catch (\Exception $exception) {
            //throw $th;
            return $this->error('', $exception->getMessage(), 404);
        }
    }

    // Helper method to add the product to the session cart
    public function addToSessionCart($product)
    {
        // Get the current cart items from the session or default to an empty array
        $cartItems = session()->get('cart', []);
        // Check if the product_id already exists in the cart
        $index = array_search($product->id, array_column($cartItems, 'product_id'));

        if ($index !== false) {
            // If the product_id exists, increment the quantity
            $cartItems[$index]['quantity'] += 1;
        } else {
            // If the product_id does not exist, add a new item to the cart
            $cartItems[] = [
                'product_id' => $product->id,
                'quantity' => 1,
            ];
        }

        // Store the updated cart items in the session
        session(['cart' => $cartItems]);

        return session('cart', []);
    }
}
