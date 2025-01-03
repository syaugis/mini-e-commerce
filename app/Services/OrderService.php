<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\MidtransService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderService
{
    protected $orderRepository;
    protected $midtransService;
    protected $cartRepository;
    protected $userRepository;

    public function __construct(OrderRepository $orderRepository,  MidtransService $midtransService, CartRepository $cartRepository, UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->midtransService = $midtransService;
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

            $totalPrice = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $orderId = 'ORD-' . time() . '-' .  strtoupper(uniqid());

            $order = $this->orderRepository->store([
                'user_id' => $cart->user->id,
                'order_id' => $orderId,
                'shipping_address_id' => $cart->user->defaultAddress->id,
                'total_price' => $totalPrice,
            ]);

            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->product->price * $item->quantity,
                ]);
            }

            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->decrement('stock', $item->quantity);
                }
            }

            $order->shippingAddress()->create([
                'order_id' => $order->id,
                'address' => $shippingAddress->address,
                'city' => $shippingAddress->city,
                'postcode' => $shippingAddress->postcode,
                'phone' => $shippingAddress->phone,
            ]);

            $snapToken = app(MidtransService::class)->createSnapToken($order);
            $order->payments()->create(['snap_token' => $snapToken]);

            $cart->items()->delete();
            $this->cartRepository->destroy($cartId);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return response()->json(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        DB::commit();

        return response()->json(['success' => true, 'data' => $order, 'payment' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken], Response::HTTP_CREATED);
    }


    public function store($data)
    {
        $validator = Validator::make($data, [
            'status' => 'required|integer|in:0,1,2,3,4',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $order = $this->orderRepository->store($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return ['error' => 'Unable to create data'];
        }
        DB::commit();

        return $order;
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'status' => 'required|integer|in:0,1,2,3,4',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        $order = $this->orderRepository->getById($id);

        if ($data['status'] < $order->status) {
            return ['error' => 'You cannot revert the status to a previous stage'];
        }

        DB::beginTransaction();
        try {
            $order = $this->orderRepository->update($data, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return ['error' => 'Unable to update data'];
        }
        DB::commit();

        return $order;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $order = $this->orderRepository->destroy($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return ['error' => 'Unable to delete data'];
        }
        DB::commit();

        return $order;
    }
}
