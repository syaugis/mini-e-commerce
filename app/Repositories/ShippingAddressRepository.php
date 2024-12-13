<?php

namespace App\Repositories;

use App\Models\ShippingAddress;

class ShippingAddressRepository
{
    protected $shippingAddress;

    public function __construct(ShippingAddress $shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    public function exists($id): bool
    {
        return $this->shippingAddress->where('id', $id)->exists();
    }


    public function getById($id): ?ShippingAddress
    {
        return $this->shippingAddress->findOrFail($id);
    }

    public function findByUserId($userId)
    {
        return $this->shippingAddress->where('user_id', $userId)->get();
    }

    public function setToDefault($userId)
    {
        return $this->shippingAddress
            ->where('user_id', $userId)
            ->where('is_default', 1)
            ->update(['is_default' => 0]);
    }

    public function store($data): ShippingAddress
    {
        $shippingAddress = new $this->shippingAddress;
        $shippingAddress->user_id = $data['user_id'];
        $shippingAddress->address = $data['address'];
        $shippingAddress->city = $data['city'];
        $shippingAddress->postcode = $data['postcode'];
        $shippingAddress->phone = $data['phone'];
        $shippingAddress->is_default =  $data['is_default'];
        $shippingAddress->save();

        return $shippingAddress;
    }

    public function update($data, $id): ShippingAddress
    {
        $shippingAddress = $this->getById($id);
        $shippingAddress->user_id = $data['user_id'];
        $shippingAddress->address = $data['address'];
        $shippingAddress->city = $data['city'];
        $shippingAddress->postcode = $data['postcode'];
        $shippingAddress->phone = $data['phone'];
        $shippingAddress->is_default = $data['is_default'];
        $shippingAddress->update();

        return $shippingAddress;
    }
}
