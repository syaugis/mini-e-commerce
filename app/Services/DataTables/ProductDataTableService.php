<?php

namespace App\Services\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductDataTableService extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->addColumn('price', function ($query) {
                return $query->formattedPrice;
            })
            ->addColumn('image', function ($query) {
                if ($query->productImages->isNotEmpty()) {
                    $url = asset("storage/" . $query->productImages->first()->image_path);
                } else {
                    $url = asset('images/error/no_image.png');
                }
                return '<img src="' . $url . '" class="img-rounded" style="max-height: 120px;" align="center"/>';
            })
            ->addColumn('description', function ($query) {
                $description = e($query->description);
                $maxLength = 40;
                if (strlen($description) > $maxLength) {
                    $description = substr($description, 0, $maxLength) . '...';
                }
                return '<divs">' . nl2br($description) . '</div>';
            })
            ->addColumn('action', 'admin.product.action')
            ->rawColumns(['action', 'image', 'description']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['productCategory:id,name', 'productImages:id,product_id,image_path'])
            ->select('products.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('products-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('image')
                ->orderable(false)
                ->searchable(false)
                ->width(100),
            Column::make('name'),
            Column::make('description')
                ->orderable(false)
                ->searchable(false),
            Column::make('price')
                ->data('price')
                ->name('products.price'),
            Column::make('stock'),
            Column::make('category_id')
                ->data('product_category.name')
                ->name('productCategory.name')
                ->title('Category'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)
                ->width(60)
                ->addClass('text-center hide-search'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Products_' . date('YmdHis');
    }
}
