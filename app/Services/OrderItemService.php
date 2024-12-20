<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Repositories\OrderItemRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderItemService
{
    protected $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function getAll(): Collection
    {
        return $this->orderItemRepository->getAll();
    }

    public function getById($id): OrderItem
    {
        return $this->orderItemRepository->getById($id);
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'product_price' => 'required|numeric|min:0|max:99999999.99',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $orderItem = $this->orderItemRepository->store($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return ['error' => 'Unable to create order item'];
        }
        DB::commit();

        return $orderItem;
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'product_price' => 'required|numeric|min:0|max:99999999.99',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $orderItem = $this->orderItemRepository->update($data, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return ['error' => 'Unable to update order item'];
        }
        DB::commit();

        return $orderItem;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $product = $this->orderItemRepository->destroy($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return ['error' => 'Unable to delete data'];
        }
        DB::commit();

        return $product;
    }
}
