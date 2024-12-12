<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAll(): Collection
    {
        return $this->orderRepository->getAll();
    }

    public function getById($id): Order
    {
        return $this->orderRepository->getById($id);
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
