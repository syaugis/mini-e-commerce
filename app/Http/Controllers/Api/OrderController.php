<?php

namespace App\Http\Controllers\Api;

use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function show(Request $request)
    {
        $userId = $request->user()->id;
        return $this->orderService->getAllUserOrder($userId);
    }

    public function checkout(Request $request)
    {
        $user = $request->user();

        if (!$user->cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        $cartId = $user->cart->id;

        return $this->orderService->checkout($cartId);
    }
}
