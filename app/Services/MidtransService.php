<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function handleNotification()
    {
        $notification = new Notification();

        return $notification;
    }

    public function createSnapToken(Order $order): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $order->total_price,
            ],
            'item_details' => $this->mapItemsToDetails($order),
            'customer_details' => $this->getCustomerDetails($order),
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function isSignatureKeyVerified(): bool
    {
        $notification = new Notification();
        $localSignatureKey = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . Config::$serverKey);

        return $localSignatureKey === $notification->signature_key;
    }

    public function getOrder(): Order
    {
        $notification = new Notification();

        return Order::where('order_id', $notification->order_id)->first();
    }

    public function getStatus(): string
    {
        $notification = new Notification();
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        return match ($transactionStatus) {
            'capture' => ($fraudStatus == 'accept') ? 'success' : 'pending',
            'settlement' => 'success',
            'deny' => 'failed',
            'cancel' => 'cancel',
            'expire' => 'expire',
            'pending' => 'pending',
            default => 'unknown',
        };
    }

    private function mapItemsToDetails(Order $order): array
    {
        return $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'price' => $item->product_price,
                'quantity' => $item->quantity,
                'name' => $item->product_name,
            ];
        })->toArray();
    }

    private function getCustomerDetails(Order $order): array
    {
        $user = $order->user;

        return [
            'first_name' => $user->name,
            'email' => $user->email,
            'shipping_address' => $this->getShippingAddress($order),
        ];
    }

    private function getShippingAddress(Order $order): array
    {
        $address = $order->shippingAddress;

        return [
            'first_name' => $order->user->name,
            'address' => $address->address,
            'city' => $address->city,
            'postal_code' => $address->postcode,
            'phone' => $address->phone,
        ];
    }
}
