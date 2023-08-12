<?php
namespace App\Repositories\Cart;

use App\Repositories\Cart\CartRepositoryInterface;
use Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class CartRepository implements CartRepositoryInterface
{
    public function getCart()
    {
        
        try {
            $user = Auth::user();
            
            $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
            $cartCount = $cartItems->sum('quantity');

            return (object) 
            [
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching cart items: ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => $user->id,
            ]);

            throw new \RuntimeException('Failed to fetch cart items', 500, $e);
        }
    }

    public function store($request)
    {
        try {
            $cartItems = $request['cartItems'];


            foreach ($cartItems as $cartItemData) {
                $find = Cart::where('product_id', $cartItemData['product']['id'])->where('user_id', Auth::user()->id)->first();
    
                if (!$find) {
                    $cartItem = new Cart([
                        'product_id' => $cartItemData['product']['id'],
                        'quantity' => $cartItemData['quantity'],
                        'user_id' => Auth::user()->id,
                    ]);
                    $cartItem->save();
                } else {
                    $find->quantity = $cartItemData['quantity'];
                    $find->update();
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to save cart items: ' . $e->getMessage(), [
                'method' => __METHOD__,
                'user_id' => Auth::user()->id,
            ]);

            throw new \RuntimeException('Failed to save cart items', 500, $e);
        }
    }
}
