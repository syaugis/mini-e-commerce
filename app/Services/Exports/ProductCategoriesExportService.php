<?php

namespace App\Services\Exports;

use App\Repositories\ProductCategoryRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;

class ProductCategoriesExportService implements FromQuery, WithCustomChunkSize, WithHeadings, WithMapping
{
    use Exportable;

    protected $productCategoryRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepository)
    {
        $this->productCategoryRepository = $productCategoryRepository;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return $this->productCategoryRepository->getQueryAll();
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
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param mixed $productCategory
     * @return array
     */
    public function map($productCategory): array
    {
        return [
            $productCategory->id,
            $productCategory->name,
            $productCategory->created_at,
            $productCategory->updated_at,
        ];
    }
}
