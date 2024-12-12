<?php

namespace App\Repositories;

use App\Models\OrderItem;

class OrderItemRepository
{
    protected $orderItem;

    public function __construct(OrderItem $orderItem)
    {
        $this->orderItem = $orderItem;
    }

    public function getAll()
    {
        return $this->orderItem->with('order', 'product')->get();
    }

    public function getById($id): ?OrderItem
    {
        return $this->orderItem->with('order', 'product')->findOrFail($id);
    }

    public function store($data): OrderItem
    {
        $orderItem = new $this->orderItem;
        $orderItem->quantity = $data['quantity'];
        $orderItem->save();

        return $orderItem;
    }

    public function update($data, $id): OrderItem
    {
        $orderItem = $this->orderItem->findOrFail($id);
        $orderItem->quantity = $data['quantity'];
        $orderItem->save();

        return $orderItem;
    }

    public function destroy($id): OrderItem
    {
        $orderItem = $this->orderItem->findOrFail($id);
        $orderItem->delete();

        return $orderItem;
    }
}