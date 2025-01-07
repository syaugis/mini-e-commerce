<?php

namespace App\Services\Imports;

use App\Models\Product;
use App\Models\User;
use App\Notifications\NewProductNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImportService implements ToModel, WithBatchInserts, WithChunkReading, WithValidation, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): null
    {
        $data = [
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'category_id' => $row['category_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        Product::insert($data);

        $product = Product::latest()->first();
        $imagePaths = explode(';', $row['image_paths']);
        foreach ($imagePaths as $url) {
            $this->downloadAndStoreImage($url, $product);
        }

        $users = User::where('role', 1)->get();
        Notification::send($users, new NewProductNotification($product));

        return null;
    }

    /**
     * @param string $url
     * @param Product $product
     */
    private function downloadAndStoreImage(string $url, Product $product)
    {
        try {
            $imageContent = Http::get($url)->body();
            $fileName = 'img_product/' . uniqid() . '.' . pathinfo($url, PATHINFO_EXTENSION);

            Storage::disk('public')->put($fileName, $imageContent);

            $product->productImages()->create(['image_path' => $fileName]);
        } catch (\Exception $e) {
            Log::error('Failed to download image: ' . $url . ' - Error: ' . $e->getMessage());
        }
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'stock' => 'required|integer|min:0|max:99999999',
            'category_id' => 'nullable|integer|exists:product_categories,id',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name field must be a string.',
            'name.max' => 'The name field must not exceed 255 characters.',
            'description.string' => 'The description field must be a string.',
            'description.max' => 'The description field must not exceed 1000 characters.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price field must be a number.',
            'price.min' => 'The price field must be at least 0.',
            'price.max' => 'The price field must not exceed 99999999.99.',
            'stock.required' => 'The stock field is required.',
            'stock.integer' => 'The stock field must be an integer.',
            'stock.min' => 'The stock field must be at least 0.',
            'stock.max' => 'The stock field must not exceed 99999999.',
            'category_id.integer' => 'The category_id field must be an integer.',
            'category_id.exists' => 'The selected category_id is invalid.',
        ];
    }

    /**
     * @return int
     */
    public function headingRow(): int
    {
        return 1;
    }
}
