<?php

namespace Renatio\BackupManager\Listeners;

use Renatio\BackupManager\Classes\Notification;
use Spatie\Backup\Events\CleanupHasFailed;

class CleanupFailed
{

    public function handle(CleanupHasFailed $event)
    {
        (new Notification)->send($event);
    }
}
