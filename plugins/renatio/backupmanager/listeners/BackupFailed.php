<?php

namespace Renatio\BackupManager\Listeners;

use Renatio\BackupManager\Classes\Notification;
use Spatie\Backup\Events\BackupHasFailed;

class BackupFailed
{

    public function handle(BackupHasFailed $event)
    {
        (new Notification)->send($event);
    }
}
