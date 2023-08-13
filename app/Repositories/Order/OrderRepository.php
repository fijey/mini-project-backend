<?php

namespace App\Repositories\Order;

use App\Repositories\Order\OrderhRepositoryInterface;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;

class OrderRepository implements OrderRepositoryInterface
{

    public function getOrderList(){
        try {
            $user = auth()->user();

           $order =  Order::with('orderDetails','orderDetails.product')->where('user_id', $user->id)->get();

           return OrderResource::collection($order);
         
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error during get data order: ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => $user->id,
            ]);

            // Rethrow the exception as a RuntimeException
            throw new \RuntimeException('An error occurred during get data order.', 500, $e);
        }
    }
    public function store($request)
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
            ]);

            foreach ($cartItems as $cartItem) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'sub_total' => $cartItem->product->price * $cartItem->quantity,
                    'quantity' => $cartItem->quantity,
                    'user_id' => $user->id,
                ]);
            }

            // Clear the user's cart
            $cartItems->each->delete();

         
        } catch (\Exception $e) {
            // Log the exception
            Log::error('Error during checkout: ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => $user->id,
            ]);

            // Rethrow the exception as a RuntimeException
            throw new \RuntimeException('An error occurred during checkout.', 500, $e);
        }
    }
}
