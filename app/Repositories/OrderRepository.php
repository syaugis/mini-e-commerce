<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getAllUserOrder($id)
    {
        return $this->order->with('shippingAddress', 'items')
            ->where('user_id', '=', $id)
            ->get();
    }

    public function getById($id): ?Order
    {
        return $this->order->with('shippingAddress', 'items')->findOrFail($id);
    }

    public function store($data): Order
    {
        $order = new $this->order;
        $order->user_id = $data['user_id'];
        $order->shipping_address_id  = $data['shipping_address_id'];
        $order->total_price  = $data['total_price'];
        $order->status = 0;
        $order->save();

        return $order;
    }

    public function update($data, $id): Order
    {
        $order = $this->order->findOrFail($id);
        $order->status = $data['status'];
        $order->save();

        return $order;
    }

    public function destroy($id): Order
    {
        $order = $this->order->findOrFail($id);
        $order->delete();

        return $order;
    }
}
