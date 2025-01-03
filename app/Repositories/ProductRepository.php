<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductRepository
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getAll()
    {
        return $this->product->with('productCategory', 'productImages')->all();
    }

    public function getQueryAll()
    {
        return $this->product->query()->with('productCategory');
    }

    public function getById($id): ?Product
    {
        return $this->product->with('productCategory', 'productImages')->findOrFail($id);
    }

    public function getFilteredAndSortedProducts($filters)
    {
        $query = $this->product->query();

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['sort_by_price'])) {
            $query->orderBy('price', $filters['sort_by_price']);
        }

        return $query->with('productCategory', 'productImages')->paginate(10);
    }

    public function store($data): Product
    {
        $product = new $this->product;
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->productCategory()->associate($data['category_id']);
        $product->save();

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $image->store('img_product', 'public');
                $product->productImages()->create(['image_path' => $path]);
            }
        }

        return $product;
    }

    public function update($data, $id): Product
    {
        $product = $this->product->with('productImages')->findOrFail($id);
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->stock = $data['stock'];
        $product->productCategory()->associate($data['category_id']);
        $product->update();

        if (isset($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $image->store('img_product', 'public');
                $product->productImages()->create(['image_path' => $path]);
            }
        }

        if (isset($data['delete_images'])) {
            foreach ($data['delete_images'] as $imageId) {
                $image = $product->productImages()->find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        return $product;
    }

    public function destroy($id): Product
    {
        $product = $this->product->with('productImages')->findOrFail($id);

        foreach ($product->productImages as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $product->delete();

        return $product;
    }
}
