<?php

namespace App\Http\Controllers\Api;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCart(Request $request)
    {
        $user = $request->user();
        if (!$user->cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }
        $cartId = $user->cart->id;
        return $this->cartService->getCart($cartId);
    }

    public function addItem(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?? $this->cartService->createCart(['user_id' => $user->id]);

        return $this->cartService->addItemToCart($cart->id, $request->all());
    }

    public function removeItem(Request $request, $productId)
    {
        $user = $request->user();
        if (!$user->cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }
        $cartId = $user->cart->id;
        return $this->cartService->removeItemFromCart($cartId, $productId);
    }

    public function updateItemQuantity(Request $request, $productId)
    {
        $user = $request->user();
        if (!$user->cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }
        $cartId = $user->cart->id;

        return $this->cartService->updateItemQuantity($cartId, $productId, $request->input('quantity'));
    }

    public function clearCart(Request $request)
    {
        $user = $request->user();
        if (!$user->cart) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }
        $cartId = $user->cart->id;
        return $this->cartService->clearCart($cartId);
    }
}
