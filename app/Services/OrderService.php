<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class OrderService
{
    protected $orderRepository;
    protected $cartRepository;
    protected $userRepository;

    public function __construct(OrderRepository $orderRepository, CartRepository $cartRepository, UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllUserOrder($id): Collection
    {
        return $this->orderRepository->getAllUserOrder($id);
    }

    public function getById($id): Order
    {
        return $this->orderRepository->getById($id);
    }

    public function checkout($cartId): JsonResponse
    {
        if (!$this->cartRepository->exists($cartId)) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();
        try {
            $cart = $this->cartRepository->getById($cartId);
            $cartItems = $cart->items;
            $shippingAddress = $cart->user->defaultAddress;

            if ($cartItems->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty'], Response::HTTP_BAD_REQUEST);
            }

            if (!$shippingAddress) {
                return response()->json(['success' => false, 'message' => 'Default Shipping address not found'], Response::HTTP_BAD_REQUEST);
            }

            $totalPrice = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $order = $this->orderRepository->store([
                'user_id' => $cart->user->id,
                'shipping_address_id' => $cart->user->defaultAddress->id,
                'total_price' => $totalPrice,
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            $order->shippingAddress()->create([
                'order_id' => $order->id,
                'address' => $shippingAddress->address,
                'city' => $shippingAddress->city,
                'postcode' => $shippingAddress->postcode,
                'phone' => $shippingAddress->phone,
            ]);

            $cart->items()->delete();
            $this->cartRepository->destroy($cartId);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return response()->json(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::commit();

        return response()->json(['success' => true, 'data' => $order], Response::HTTP_CREATED);
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'status' => 'required|int',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $product = $this->orderRepository->store($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to create data');
        }
        DB::commit();

        return $product;
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'status' => 'required|int',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $product = $this->orderRepository->update($data, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to update data');
        }
        DB::commit();

        return $product;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $product = $this->orderRepository->destroy($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete data');
        }
        DB::commit();

        return $product;
    }
}
