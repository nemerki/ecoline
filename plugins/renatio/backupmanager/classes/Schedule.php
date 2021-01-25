<?php

namespace Renatio\BackupManager\Classes;

use Renatio\BackupManager\Models\Settings;

class Schedule
{

    protected $settings;

    protected $schedule;

    public function __construct($schedule)
    {
        $this->schedule = $schedule;
        $this->settings = Settings::instance();
    }

    public function databaseBackup()
    {
        $this->scheduleCommand('backup:run', $this->settings->db_scheduler, ['--only-db']);

        return $this;
    }

    public function appBackup()
    {
        $this->scheduleCommand('backup:run', $this->settings->app_scheduler);

        return $this;
    }

    public function cleanOldBackups()
    {
        $this->scheduleCommand('backup:clean', $this->settings->clean_scheduler);

        return $this;
    }

    protected function scheduleCommand($command, $when, $options = [])
    {
        if (!$when) {
            return false;
        }

        return $this->schedule->command($command, $options)
            ->$when()
            ->sendOutputTo(storage_path('app/backup.log'));
    }
}
