<?php

namespace Renatio\BackupManager\Providers;

use Illuminate\Notifications\NotificationServiceProvider;
use Illuminate\Support\ServiceProvider;
use Olssonm\BackupShield\BackupShieldServiceProvider;
use Spatie\Backup\BackupServiceProvider as LaravelBackupServiceProvider;

class BackupServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register(LaravelBackupServiceProvider::class);
        $this->app->register(BackupShieldServiceProvider::class);

        /*
         * Required to omit error in October
         */
        $this->app->register(NotificationServiceProvider::class);
    }
}
