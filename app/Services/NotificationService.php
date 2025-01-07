<?php

namespace App\Services;

use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    public function getAllNotifications($perPage = 10)
    {
        $notifications = DatabaseNotification::paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => new NotificationCollection($notifications),
        ], Response::HTTP_OK);
    }

    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'data' =>  new NotificationResource($notification),
        ], Response::HTTP_OK);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification has been deleted successfully',
            'data' =>  new NotificationResource($notification),
        ], Response::HTTP_OK);
    }

    public function clearAllNotifications()
    {
        DatabaseNotification::where('notifiable_id', Auth::id())->delete();
    }
}
