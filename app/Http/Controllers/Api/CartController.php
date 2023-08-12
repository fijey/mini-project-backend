<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cart;
use Auth;
use App\Repositories\Cart\CartRepositoryInterface;
class CartController extends Controller
{
    protected $cartRepository;
    
    public function __construct(CartRepositoryInterface $cartRepository){
        $this->cartRepository = $cartRepository;
    }

    public function getCart()
    {
        try {
            $cart = $this->cartRepository->getCart();

            return response()->json(['cart' => $cart], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching cart items', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
    
            $validator = $request->validate([
                'cartItems' => 'required|array|min:1',
                'cartItems.*.product.id' => 'required|integer',
                'cartItems.*.quantity' => 'required|integer|min:1',
            ]);


            $insertCart = $this->cartRepository->store($request->all());
    
          
    
            return response()->json(['message' => 'Cart items saved successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to save cart items', 'error' => $e->getMessage()], 500);
        }
    }
}
