<?php

namespace Renatio\BackupManager\Listeners;

use Renatio\BackupManager\Classes\Notification;
use Renatio\BackupManager\Models\Backup;
use Spatie\Backup\Events\CleanupWasSuccessful;

class CleanupSuccess
{

    public function handle(CleanupWasSuccessful $event)
    {
        $this->deleteDatabaseRecords();

        (new Notification)->send($event);
    }

    protected function deleteDatabaseRecords()
    {
        foreach (Backup::all() as $backup) {
            foreach ($backup->disks() as $disk) {
                if (!$backup->exists($disk)) {
                    $backup->delete();
                }
            }
        }
    }
}
