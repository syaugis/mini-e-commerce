<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function show($id)
    {
        $notification = $this->notificationService->markAsRead($id);
        $url = $notification->getData()->data->url;

        return redirect($url);
    }

    public function marksAllRead()
    {
        $this->notificationService->markAllAsRead();

        return redirect()->back()->withSuccess(__('global-message.notifications', ['notification' => 'marked as read']));;
    }

    public function destroy($id)
    {
        $this->notificationService->deleteNotification($id);

        return redirect()->back()->withSuccess(__('global-message.delete_form', ['form' => 'Notification']));;
    }

    public function clearAll()
    {
        $this->notificationService->clearAllNotifications();

        return redirect()->back()->withSuccess(__('global-message.delete_form', ['form' => 'All notification']));;
    }
}
