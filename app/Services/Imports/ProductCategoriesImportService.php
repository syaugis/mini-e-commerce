<?php

namespace App\Services\Imports;

use App\Models\ProductCategory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductCategoriesImportService implements ToModel, WithBatchInserts, WithChunkReading, WithValidation, WithHeadingRow
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new ProductCategory([
            'name' => $row['name'],
        ]);
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
            'name' => 'required|string|max:255|unique:product_categories,name',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'name.string' => 'Name must be a string',
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
