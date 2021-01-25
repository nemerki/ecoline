<?php

namespace Renatio\BackupManager\Classes;

use Mail;
use October\Rain\Mail\Mailable;
use Renatio\BackupManager\Models\Settings;

class Notification
{

    public function send($event)
    {
        $settings = Settings::instance();

        if (!is_array($settings->notification_events) || !$settings->notification_emails) {
            return;
        }

        if (!in_array(class_basename($event), $settings->notification_events)) {
            return;
        }

        $notificationClass = 'Spatie\Backup\Notifications\\Notifications\\'.class_basename($event);
        $notification = app($notificationClass)->setEvent($event)->toMail();

        foreach ($settings->notification_emails as $email) {
            Mail::queue(
                'renatio.backupmanager::mail.notification',
                ['content' => implode('<br>', $notification->introLines)],
                function (Mailable $message) use ($email, $notification) {
                    $message->to($email);
                    $message->subject($notification->subject);
                }
            );
        }
    }
}
