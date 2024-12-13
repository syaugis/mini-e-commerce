<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function exists($cartId): bool
    {
        return $this->cart->where('id', $cartId)->exists();
    }

    public function getById($id): ?Cart
    {
        return $this->cart->with('user', 'items')->findOrFail($id);
    }

    public function store($data): Cart
    {
        $cart = new $this->cart;
        $cart->user_id = $data['user_id'];
        $cart->save();

        return $cart;
    }

    public function update($data, $id): Cart
    {
        $cart = $this->cart->findOrFail($id);
        $cart->user_id = $data['user_id'];
        $cart->update();

        return $cart;
    }

    public function destroy($id): bool
    {
        $cart = $this->cart->findOrFail($id);

        return $cart->delete();
    }
}
