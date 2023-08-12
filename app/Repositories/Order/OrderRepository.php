<?php

namespace App\Repositories\Order;

use App\Repositories\Order\OrderhRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function store($data)
    {
        try {
            $user = auth()->user();
            
            $cartItems = Cart::where('user_id', $user->id)->with('product')->get();


            // Calculate the total price
            $totalPrice = $cartItems->sum(function ($cartItem) {
                return $cartItem->product->price * $cartItem->quantity;
            });

            $invoiceNumber = Str::random(10);
            // Create a new order record
            $order = Order::create([
                'invoice' => $invoiceNumber,
                'user_id' => $user->id,
                'sub_total' => $totalPrice,
                // You might want to add more fields based on your order schema
            ]);

            foreach ($cartItems as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'sub_total' => $cartItem->product->price * $cartItem->quantity,
                    'user_id' => $user->id,
                    // You might want to add more fields based on your order details schema
                ]);
            }

            // Clear the user's cart
            $cartItems->each->delete();

            // Return a success response
            return response()->json([
                'message' => 'Checkout successful!',
                'totalPrice' => $totalPrice,
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the checkout process
            return response()->json([
                'error' => 'An error occurred during checkout.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
