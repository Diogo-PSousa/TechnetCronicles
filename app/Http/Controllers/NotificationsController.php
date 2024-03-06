<?php

namespace App\Http\Controllers;

use App\Models\VoteNotification;
use App\Models\CommentNotification;

class NotificationsController extends Controller
{
    public function index()
    {
        $authUserId = auth()->id();

        $voteNotifications = VoteNotification::with('notified', 'vote')
            ->where('notified_id', $authUserId)
            ->get();

        $commentNotifications = CommentNotification::with('notified', 'comment')
            ->where('notified_id', $authUserId)
            ->get();

        $notifications = $voteNotifications->merge($commentNotifications);

        $notifications = $notifications->sortByDesc('date_time');

        return view('pages.notifications', ['notifications' => $notifications]);
    }
}
