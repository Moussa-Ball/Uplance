<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function limit(Request $request)
    {
        return $request->user()->notifications()
            ->limit(5)->orderBy('created_at', 'DESC')->get()->toArray();
    }

    public function all(Request $request)
    {
        return $request->user()->notifications()->get()->toArray();
    }

    public function markAsRead(Request $request, $notification_id)
    {
        return $request->user()->unreadNotifications->where('id', $notification_id)->markAsRead();
        return $request->user()->notifications()->get()->toArray();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return $request->user()->notifications()->get()->toArray();
    }
}
