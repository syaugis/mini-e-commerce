<?php

namespace App\Services\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderDataTableService extends DataTable
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
            ->setRowId('id')
            ->addColumn('shipping_address', function ($query) {
                return $query->shippingAddress->address;
            })
            ->addColumn('status', function ($query) {
                $labels = [
                    0 => '<span class="badge bg-secondary">Pending</span>',
                    1 => '<span class="badge bg-primary">Paid</span>',
                    2 => '<span class="badge bg-info">Shipped</span>',
                    3 => '<span class="badge bg-success">Completed</span>',
                    4 => '<span class="badge bg-danger">Canceled</span>',
                ];
                return $labels[$query->status] ?? '<span class="badge bg-dark">Unknown</span>';
            })
            ->filterColumn('status', function ($query, $keyword) {
                $sql = 'CASE WHEN status = 0 THEN "Pending" 
                           WHEN status = 1 THEN "Paid"
                           WHEN status = 2 THEN "Shipped"
                           WHEN status = 3 THEN "Completed"
                           WHEN status = 4 THEN "Canceled" END like ?';
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->addColumn('total_price', function ($query) {
                return $query->formatted_total_price;
            })
            ->addColumn('action', 'admin.order.action')
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user:id,name', 'shippingAddress:id,order_id,address'])
            ->select('orders.*');

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orders-table')
            ->columns($this->getColumns())
            ->minifiedAjax(!empty($this->userId) ? route('admin.user.orders', $this->userId) : '')
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
        if (!empty($this->userId)) {
            return [
                Column::make('id')->title('ID'),
                Column::make('shipping_address')
                    ->title('Shipping Address'),
                Column::make('status')
                    ->title('Status'),
                Column::make('total_price')
                    ->data('total_price')
                    ->name('total_price')
                    ->title('Total Price'),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->searchable(false)
                    ->width(60)
                    ->addClass('text-center hide-search'),
            ];
        } else {
            return [
                Column::make('id')->title('ID'),
                Column::make('user')
                    ->data('user.name')
                    ->name('user.name')
                    ->title('User Name'),
                Column::make('shipping_address')
                    ->data('shipping_address')
                    ->name('shippingAddress.address')
                    ->title('Shipping Address'),
                Column::make('status')
                    ->title('Status'),
                Column::make('total_price')
                    ->data('total_price')
                    ->name('orders.total_price')
                    ->title('Total Price'),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
                    ->searchable(false)
                    ->width(60)
                    ->addClass('text-center hide-search'),
            ];
        }
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Orders_' . date('YmdHis');
    }
}
