<?php

namespace App\Repositories\Order;

use App\Repositories\Order\OrderhRepositoryInterface;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;
use App\Jobs\OrderJob;

class OrderRepository implements OrderRepositoryInterface
{

    public function getOrderList(){
        try {
            $user = auth()->user();

           $order =  Order::with('orderDetails','orderDetails.product')->where('user_id', $user->id)->get();

           return OrderResource::collection($order);
         
        } catch (\Exception $e) {

            Log::error('Error during get data order: ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => $user->id,
            ]);


            throw new \RuntimeException('An error occurred during get data order.', 500, $e);
        }
    }
    public function store($request)
    {
        try {
            $user = auth()->user();
            
            OrderJob::dispatch($user);

            
         
        } catch (\Exception $e) {

            Log::error('Error during checkout: ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => $user->id,
            ]);


            throw new \RuntimeException('An error occurred during checkout.', 500, $e);
        }
    }
}
