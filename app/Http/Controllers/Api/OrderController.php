<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Order\OrderRepositoryInterface;

class OrderController extends Controller
{   protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository){
        $this->orderRepository = $orderRepository;
    }

    public function getOrderList(){
        try {
            $order = $this->orderRepository->getOrderList();

            return response()->json([
                'message' => 'Get Order successful!',
                'data' => $order,
            ]);
        } catch (\Exception $e) {
            // Handle any exceptions that occur during the checkout process
            return response()->json([
                'error' => 'An error occurred during get Order.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {

            $insertOrder = $this->orderRepository->store($request->all());

            // Return a success response
            return response()->json([
                'message' => 'Checkout successful!',
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
