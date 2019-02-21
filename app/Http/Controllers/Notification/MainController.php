<?php

namespace App\Http\Controllers\Notification;

use App\Notification;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function check(){
        $notifications = Notification::where([
            "user_id" => auth()->id(),
            "read" => false
        ])->orderBy('updated_at','desc')->take(5)->get();
        return response()
            ->view('l.notifications',
                [
                    "notifications" => $notifications
                ])
            ->header('Content-Type','html');
    }

    public function read(){
        $notification = Notification::where([
            "user_id" => auth()->id(),
            "_id" => request('notification_id')
        ])->first();
        $notification->read = true;
        $notification->save();
        return $notification->_id;
    }
}
