<?php

namespace App\Services\Exports;

use App\Repositories\ProductRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;

class ProductsExportService implements FromQuery, WithCustomChunkSize, WithHeadings, WithMapping
{
    use Exportable;

    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return $this->productRepository->getQueryAll();
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Description',
            'Price',
            'Stock',
            'Product Category',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->description,
            $product->price,
            $product->stock,
            $product->productCategory->name,
            $product->created_at,
            $product->updated_at,
        ];
    }
}
