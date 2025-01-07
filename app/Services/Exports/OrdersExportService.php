<?php

namespace App\Services\Exports;

use App\Repositories\OrderRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;

class OrdersExportService implements FromQuery, WithCustomChunkSize, WithHeadings, WithMapping
{
    use Exportable;

    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return $this->orderRepository->getQueryAll();
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
            'User Name',
            'User Phone',
            'Status',
            'Total Price',
            'Shipping Address',
            'Order Midtrans Status',
            'Order Midtrans Snap URL',
            'Order Items',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param mixed $order
     * @return array
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->user->name,
            $order->shippingAddress->phone,
            $order->status_label,
            $order->formatted_total_price,
            $order->shippingAddress->address . ', ' . $order->shippingAddress->city . ', ' . $order->shippingAddress->postcode,
            $order->payments->last()->status,
            $order->payments->last()->snap_url,
            $order->items->map(function ($item) {
                return $item->product_name . ' (' . $item->quantity . ' items)';
            })->implode(', '),
            $order->created_at,
            $order->updated_at,
        ];
    }
}
