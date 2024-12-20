<?php

namespace App\Services\DataTables;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderItemDataTableService extends DataTable
{
    private $orderId;

    public function withOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

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
                return $query->formatted_price;
            })
            ->addColumn('total_price', function ($query) {
                return $query->total_price;
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(OrderItem $model): QueryBuilder
    {
        return $model->where('order_id', $this->orderId)->newQuery()
            ->with(['product:id,name'])
            ->select('order_items.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('order-items-table')
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
            Column::make('id')
                ->title('ID'),
            Column::make('product')
                ->data('product_name')
                ->title('Product Name'),
            Column::make('quantity')
                ->title('Quantity'),
            Column::make('product_price')
                ->title('Price'),
            Column::make('total_price')
                ->title('Total Price'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'OrderItems_' . date('YmdHis');
    }
}
