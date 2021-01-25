<?php

namespace Renatio\BackupManager\Listeners;

use Renatio\BackupManager\Classes\Notification;
use Renatio\BackupManager\Models\Backup;
use Spatie\Backup\Events\BackupWasSuccessful;

class BackupSuccess
{

    public function handle(BackupWasSuccessful $event)
    {
        Backup::createModel($event->backupDestination);

        (new Notification)->send($event);
    }
}
