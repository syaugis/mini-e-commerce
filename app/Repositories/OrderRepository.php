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

    public function getAll()
    {
        return $this->order->with('user', 'shippingAddress', 'items')->get();
    }

    public function getById($id): ?Order
    {
        return $this->order->with('user', 'shippingAddress', 'items')->findOrFail($id);
    }

    public function store($data): Order
    {
        $order = new $this->order;
        $order->status = $data['status'];
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
