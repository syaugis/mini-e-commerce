<?php

namespace App\Services\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsTemplateService implements FromArray, WithHeadings
{
    use Exportable;

    /**
     * @return array
     */
    public function array(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'name',
            'description',
            'price',
            'stock',
            'category_id',
            'image_paths'
        ];
    }
}
