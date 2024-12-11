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
        return $this->product->with('productCategory', 'productImages')->get();
    }

    public function getById($id): ?Product
    {
        return $this->product->with('productCategory', 'productImages')->findOrFail($id);
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
        $product->save();

        if (isset($data['images'])) {
            foreach ($product->productImages as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            foreach ($data['images'] as $image) {
                $path = $image->store('img_product', 'public');
                $product->productImages()->create(['image_path' => $path]);
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
