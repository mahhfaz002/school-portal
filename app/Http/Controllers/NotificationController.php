<?php

namespace App\Http\Controllers;

use App\Support\Notifications;

class NotificationController extends Controller
{
    public function index()
    {
        $items = Notifications::feedFor(auth()->user());

        return view('notifications.index', compact('items'));
    }
}
