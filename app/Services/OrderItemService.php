<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Repositories\OrderItemRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

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
            'quantity' => 'required|int',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $product = $this->orderItemRepository->store($data);
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
            'quantity' => 'required|int',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $product = $this->orderItemRepository->update($data, $id);
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
            $product = $this->orderItemRepository->destroy($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete data');
        }
        DB::commit();

        return $product;
    }
}
