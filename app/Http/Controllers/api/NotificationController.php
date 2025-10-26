<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = request()->user();

        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();

        return response()->json($notifications);
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['status' => 'read']);
        \Log::info($notification);
        return response()->json(['status' => 'success']);
    }
}
