<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    //
    public function login(LoginUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();
        // $this->mergeSessionCartWithUserCart($user);
        return new UserResource($user);
    }
    public function register(StoreUserRequest $request)
    {
        $request->validated($request->only(['first_name', 'last_name', 'email', 'password']));

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return new UserResource($user);
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out.']);
    }

    private function mergeSessionCartWithUserCart($user)
    {
        $sessionCart = session('cart', []);
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
                // Create a new cart item if the product is not in the cart
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // Clear the session cart after merging
        session(['cart' => []]);
    }
}
