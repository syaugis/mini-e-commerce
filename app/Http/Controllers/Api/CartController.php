<?php

namespace App\Http\Controllers\Api;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getCart(Request $request)
    {
        $cartId = $request->user()->cart->id;
        return $this->cartService->getCart($cartId);
    }

    public function createCart(Request $request)
    {
        return $this->cartService->createCart($request->all());
    }

    public function addItem(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart ?? $this->cartService->createCart(['user_id' => $user->id]);

        return $this->cartService->addItemToCart($cart->id, $request->all());
    }

    public function removeItem(Request $request, $productId)
    {
        $cartId = $request->user()->cart->id;
        return $this->cartService->removeItemFromCart($cartId, $productId);
    }

    public function updateItemQuantity(Request $request, $productId)
    {
        $cartId = $request->user()->cart->id;
        return $this->cartService->updateItemQuantity($cartId, $productId, $request->input('quantity'));
    }

    public function clearCart(Request $request)
    {
        $cartId = $request->user()->cart->id;
        return $this->cartService->clearCart($cartId);
    }
}
