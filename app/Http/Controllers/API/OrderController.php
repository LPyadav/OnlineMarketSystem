<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Helpers\APIResponse;

class OrderController extends Controller
{
    /**
     * Create a new order from the user's cart items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function CreateOrder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $user_id = $request->user_id;
        // Retrieve cart items for the user
        $cartItems = Cart::where('user_id', $user_id)->get();

        if ($cartItems->isEmpty()) {
            return APIResponse::error('Cart is empty', 400);
        }

        // Calculate the total order amount
        $total = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        // Use a transaction to ensure data consistency
        DB::transaction(function () use ($user_id, $cartItems, $total) {
            // Create the order
            $order = Order::create([
                'user_id' => $user_id,
                'status' => 'pending',
                'total' => $total,
            ]);

            // Create the order items from the cart items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);

                // Remove the item from the cart
                $cartItem->delete();
            }
        });
        return APIResponse::success([], 'Order created successfully');
    }

    /**
     * Get the authenticated user's orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetUserOrders(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);
        $user_id = $request->user_id;

        // Retrieve the user's orders with order items and products 
        $orders = Order::withCount(['orderItems'])
            ->where('user_id', $user_id)
            ->get();

        // Check if the user has any orders
        if ($orders->isEmpty()) {
            return APIResponse::error('No orders found for the user', 404);
        }

        return APIResponse::success(['orders' => $orders], 'Get order successfully');
    }


    /**
     * Get the authenticated user's order details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetUserOrderDetails(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);
        $order_id = $request->order_id;

        // Retrieve the user's orders with order items and products 
        $orders = Order::with(['orderItems.product.images'])
            ->where('id', $order_id)
            ->get();

        // Check if the user has any orders
        if ($orders->isEmpty()) {
            return APIResponse::error('No orders found for the user', 404);
        }

        return APIResponse::success(['orders' => $orders], 'Get order details successfully');
    }
}
