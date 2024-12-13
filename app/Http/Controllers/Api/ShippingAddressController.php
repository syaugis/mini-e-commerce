<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ShippingAddressService;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    protected $shippingAddressService;

    public function __construct(ShippingAddressService $shippingAddressService)
    {
        $this->shippingAddressService = $shippingAddressService;
    }

    public function index(Request $request)
    {
        $userId = $request->user()->id;

        return $this->shippingAddressService->getByUserId($userId);
    }

    public function store(Request $request)
    {
        $data = $request->only(['user_id', 'address', 'city', 'postcode', 'phone', 'is_default']);
        $data['user_id'] = $request->user()->id;

        return $this->shippingAddressService->store($data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['user_id', 'address', 'city', 'postcode', 'phone', 'is_default']);
        $data['user_id'] = $request->user()->id;

        return $this->shippingAddressService->update($data, $id);
    }
}
