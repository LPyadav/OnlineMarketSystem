<?php

namespace App\Http\Controllers\API;

use App\Helpers\APIResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    /**
     * Add a product to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function AddToCart(Request $request)
    {
        // Validate the request data
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);



        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id',  $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // If the product is already in the cart, update the quantity
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Otherwise, create a new cart item
            Cart::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        return APIResponse::success([], 'Product added to cart successfully');
    }

    /**
     * Remove a product from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function RemoveFromCart(Request $request)
    {
        // Validate the request data
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        // Find the cart item and delete
        Cart::find($request->cart_id)->delete();
        return APIResponse::success([], 'Product removed from cart successfully');
    }
}
