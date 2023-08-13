<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Order\OrderhRepositoryInterface;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Log;
use App\Http\Resources\OrderResource;

class OrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cartItems = Cart::where('user_id', $this->user->id)->with('product')->get();

        // Calculate the total price
        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        $invoiceNumber = Str::random(10);

        // Create a new order record
        $order = Order::create([
            'invoice' => $invoiceNumber,
            'user_id' => $this->user->id,
            'sub_total' => $totalPrice,
        ]);

        foreach ($cartItems as $cartItem) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product->id,
                'sub_total' => $cartItem->product->price * $cartItem->quantity,
                'quantity' => $cartItem->quantity,
                'user_id' => $this->user->id,
            ]);
        }

        // Clear the user's cart
        $cartItems->each->delete();

    }
}
