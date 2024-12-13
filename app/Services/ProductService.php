<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAll(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function getById($id): Product
    {
        return $this->productRepository->getById($id);
    }

    public function getProducts($filters)
    {
        try {
            if (isset($filters['sort_by_price']) && !in_array($filters['sort_by_price'], ['asc', 'desc'])) {
                return response()->json(['success' => false, 'message' => 'Invalid sort_by_price value. Allowed values: asc, desc.'], Response::HTTP_BAD_REQUEST);
            }

            if (isset($filters['category_id']) && !is_numeric($filters['category_id'])) {
                return response()->json(['success' => false, 'message' => 'Invalid category_id. It must be numeric.'], Response::HTTP_BAD_REQUEST);
            }

            $products = $this->productRepository->getFilteredAndSortedProducts($filters);

            if ($products->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No products found for the given filters.'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['success' => true, 'data' => $products], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred while fetching products. Please try again later.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:product_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $product = $this->productRepository->store($data);
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:product_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'file|image|max:2048',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()];
        }

        DB::beginTransaction();
        try {
            $product = $this->productRepository->update($data, $id);
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
            $product = $this->productRepository->destroy($id);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            throw new InvalidArgumentException('Unable to delete data');
        }
        DB::commit();

        return $product;
    }
}
