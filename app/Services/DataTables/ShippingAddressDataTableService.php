<?php

namespace App\Services\DataTables;

use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ShippingAddressDataTableService extends DataTable
{
    private $userId;

    public function withUserId($userId)
    {
        $this->userId = $userId;
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
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ShippingAddress $model): QueryBuilder
    {
        return $model->where('user_id', $this->userId)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('shipping-addresses-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.user.shipping-addresses', $this->userId))
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
            Column::make('phone')
                ->title('Phone'),
            Column::make('address')
                ->title('Address'),
            Column::make('city')
                ->title('City'),
            Column::make('postcode')
                ->title('Postal Code'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ShippingAddresses_' . date('YmdHis');
    }
}
