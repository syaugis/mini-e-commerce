<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $notifications = $this->notificationService->getAllNotifications();

        return $notifications;
    }

    public function markAsRead($id)
    {
        $notification = $this->notificationService->markAsRead($id);

        return $notification;
    }

    public function destroy($id)
    {
        $notification = $this->notificationService->deleteNotification($id);

        return $notification;
    }
}
