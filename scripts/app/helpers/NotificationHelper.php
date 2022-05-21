<?php

class NotificationHelper extends  Phalcon\Mvc\User\Component
{
    static function normalize ($notification)
    {
        if ($notification instanceof Notification)
            $notification = $notification->toArray();
            //$notification = Utils::objectToArray($notification, self::$fields);

        $notification['created_txt'] = Utils::formatTanggal($notification['created'], false, false, true);

        return $notification;
    }
}