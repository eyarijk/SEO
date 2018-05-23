<?php

namespace App\Classes;

use App\Notification;

class NotificationSend
{
    /*
        Vars for notification
    */

    public $description;
    public $user;
    public $status;

    static public function send($user,$description,$status)
    {
        $notification = new Notification();
        $notification->user_id = $user;
        $notification->description = $description;
        $notification->status = $status;
        $notification->save();
    }


}