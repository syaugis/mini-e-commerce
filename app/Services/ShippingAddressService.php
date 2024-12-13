<?php

namespace App\Services;

use App\Repositories\ShippingAddressRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class ShippingAddressService
{
    protected $shippingAddressRepository;

    public function __construct(ShippingAddressRepository $shippingAddressRepository)
    {
        $this->shippingAddressRepository = $shippingAddressRepository;
    }

    public function getByUserId($userId)
    {
        try {
            $addresses = $this->shippingAddressRepository->findByUserId($userId);

            if ($addresses->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No shipping addresses found for this user.'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['success' => true, 'data' => $addresses], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch shipping addresses: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postcode' => 'required|string|max:10',
            'phone' => 'required|string|max:15',
            'is_default' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            if ($data['is_default'] == 1) {
                $this->shippingAddressRepository->setToDefault($data['user_id']);
            }
            $shippingAddress = $this->shippingAddressRepository->store($data);

            return response()->json(['success' => true, 'data' => $shippingAddress], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to add shipping address: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'address' => 'required|string',
            'city' => 'required|string',
            'postcode' => 'required|string',
            'phone' => 'required|string',
            'is_default' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $shippingAddress = $this->shippingAddressRepository->getById($id);

        if (!$this->shippingAddressRepository->exists($id)) {
            return response()->json(['success' => false, 'message' => 'Shipping address not found'], Response::HTTP_NOT_FOUND);
        }

        if ($shippingAddress->user_id !== $data['user_id']) {
            return response()->json(['success' => false, 'message' => 'You are not authorized to update this address'], Response::HTTP_FORBIDDEN);
        }

        try {
            if ($data['is_default'] == 1) {
                $this->shippingAddressRepository->setToDefault($data['user_id']);
            }

            $shippingAddress = $this->shippingAddressRepository->update($data, $id);
            return response()->json(['success' => true, 'data' => $shippingAddress], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
