<?php

namespace App\Services;

use App\Models\ProductCategory;
use App\Repositories\ProductCategoryRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductCategoryService
{
    protected $productCategoryRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function getAll(): Collection
    {
        return $this->productCategoryRepository->getAll();
    }

    public function getById($id): ProductCategory
    {
        return $this->productCategoryRepository->getById($id);
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:product_categories,name',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $productCategory = $this->productCategoryRepository->store($data);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return ['error' => 'Unable to create data'];
        }
        DB::commit();

        return $productCategory;
    }

    public function update($data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:product_categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $productCategory = $this->productCategoryRepository->update($data, $id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return ['error' => 'Unable to update data'];
        }
        DB::commit();

        return $productCategory;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $productCategory = $this->productCategoryRepository->destroy($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return ['error' => 'Unable to delete data'];
        }
        DB::commit();

        return $productCategory;
    }
}
