<?php

use Renatio\BackupManager\Listeners\BackupFailed;
use Renatio\BackupManager\Listeners\BackupSuccess;
use Renatio\BackupManager\Listeners\CleanupFailed;
use Renatio\BackupManager\Listeners\CleanupSuccess;
use Spatie\Backup\Events\BackupHasFailed;
use Spatie\Backup\Events\BackupWasSuccessful;
use Spatie\Backup\Events\CleanupHasFailed;
use Spatie\Backup\Events\CleanupWasSuccessful;

Event::listen(BackupWasSuccessful::class, BackupSuccess::class);
Event::listen(CleanupWasSuccessful::class, CleanupSuccess::class);
Event::listen(BackupHasFailed::class, BackupFailed::class);
Event::listen(CleanupHasFailed::class, CleanupFailed::class);
