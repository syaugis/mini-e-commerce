<?php

namespace App\Repositories;

use App\Models\CartItem;

class CartItemRepository
{
    protected $cartItem;

    public function __construct(CartItem $cartItem)
    {
        $this->cartItem = $cartItem;
    }

    public function itemExists($cartId, $productId): bool
    {
        return $this->cartItem->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->exists();
    }

    public function getItems($cartId)
    {
        return $this->cartItem->where('cart_id', $cartId)->get();
    }

    public function addItem(int $cartId, $itemData): CartItem
    {
        $item = $this->cartItem->where('cart_id', $cartId)
            ->where('product_id', $itemData['product_id'])
            ->first();

        if ($item) {
            $item->quantity += $itemData['quantity'];
        } else {
            $item = new CartItem($itemData);
            $item->cart_id = $cartId;
        }

        $item->save();
        return $item;
    }

    public function updateQuantity(int $cartId, int $productId, int $quantity): CartItem
    {
        $item = $this->cartItem->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->firstOrFail();

        $item->quantity = $quantity;
        $item->save();

        return $item;
    }

    public function removeItem(int $cartId, int $productId): bool
    {
        return $this->cartItem->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->delete();
    }

    public function clearCart(int $cartId): bool
    {
        return $this->cartItem->where('cart_id', $cartId)->delete();
    }
}
