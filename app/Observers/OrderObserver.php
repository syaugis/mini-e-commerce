<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $admins = User::where('role', 0)->get();
        Notification::send($admins, new NewOrderNotification($order));
    }

    /**
     * Handle the "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            $order->user->notify(new OrderStatusChangedNotification($order));

            $admins = User::where('role', 0)->get();
            Notification::send($admins, new OrderStatusChangedNotification($order));
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
