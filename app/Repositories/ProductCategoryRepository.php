<?php

namespace App\Repositories;

use App\Models\ProductCategory;

class ProductCategoryRepository
{
    protected $productCategory;

    public function __construct(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    public function getAll()
    {
        return $this->productCategory->get();
    }

    public function getById($id): ?ProductCategory
    {
        return $this->productCategory->findOrFail($id);
    }

    public function store($data): ProductCategory
    {
        $productCategory = new $this->productCategory;
        $productCategory->name = $data['name'];
        $productCategory->save();

        return $productCategory;
    }

    public function update($data, $id): ProductCategory
    {
        $productCategory = $this->productCategory->findOrFail($id);
        $productCategory->name = $data['name'];
        $productCategory->save();

        return $productCategory;
    }

    public function destroy($id): ProductCategory
    {
        $productCategory = $this->productCategory->findOrFail($id);
        $productCategory->delete();

        return $productCategory;
    }
}
