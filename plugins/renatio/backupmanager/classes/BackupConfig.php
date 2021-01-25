<?php

namespace Renatio\BackupManager\Classes;

use Renatio\BackupManager\Models\Settings;
use Spatie\Backup\Notifications\Notifications\BackupHasFailed;
use Spatie\Backup\Notifications\Notifications\BackupWasSuccessful;
use Spatie\Backup\Notifications\Notifications\CleanupHasFailed;
use Spatie\Backup\Notifications\Notifications\CleanupWasSuccessful;
use Spatie\Backup\Notifications\Notifications\HealthyBackupWasFound;
use Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFound;
use Spatie\Backup\Tasks\Cleanup\Strategies\DefaultStrategy;

class BackupConfig
{

    protected $settings;

    public function __construct()
    {
        $this->settings = Settings::instance();
    }

    public function init()
    {
        $this->setSource();

        $this->setDestination();

        $this->setCleanupStrategy();

        $this->setPasswordProtection();

        $this->disableNotifications();
    }

    protected function setSource()
    {
        config([
            'backup.backup.source.files.include' => $this->settings->getIncludePaths(),
            'backup.backup.source.files.exclude' => $this->settings->getExcludePaths(),
            'backup.backup.source.files.followLinks' => $this->settings->follow_links,
            'backup.backup.source.databases' => $this->settings->databases ?: [config('database.default')],
            'backup.backup.database_dump_compressor' => $this->settings->compression(),
        ]);

        $this->excludeDatabaseTables();
    }

    protected function setDestination()
    {
        config([
            'backup.backup.name' => $this->settings->backup_name,
            'backup.backup.destination' => [
                'filename_prefix' => $this->settings->filename_prefix,
                'disks' => $this->settings->disks,
            ],
        ]);
    }

    protected function setCleanupStrategy()
    {
        config([
            'backup.cleanup' => [
                'strategy' => DefaultStrategy::class,
                'defaultStrategy' => [
                    'keepAllBackupsForDays' => $this->settings->keep_all,
                    'keepDailyBackupsForDays' => $this->settings->keep_daily,
                    'keepWeeklyBackupsForWeeks' => $this->settings->keep_weekly,
                    'keepMonthlyBackupsForMonths' => $this->settings->keep_monthly,
                    'keepYearlyBackupsForYears' => $this->settings->keep_yearly,
                    'deleteOldestBackupsWhenUsingMoreMegabytesThan' => $this->settings->delete_oldest_when_mb,
                ],
            ],
        ]);
    }

    protected function setPasswordProtection()
    {
        config([
            'backup-shield.password' => $this->settings->zip_password ?: null,
            'backup-shield.encryption' => $this->settings->zip_password_encryption
                ? constant('\Olssonm\BackupShield\Encryption::'.$this->settings->zip_password_encryption)
                : null,
        ]);
    }

    protected function disableNotifications()
    {
        config([
            'backup.notifications.notifications' => [
                BackupHasFailed::class => [],
                UnhealthyBackupWasFound::class => [],
                CleanupHasFailed::class => [],
                BackupWasSuccessful::class => [],
                HealthyBackupWasFound::class => [],
                CleanupWasSuccessful::class => [],
            ],
        ]);
    }

    protected function excludeDatabaseTables()
    {
        if (!$this->settings->exclude_tables) {
            return;
        }

        foreach (config('backup.backup.source.databases') as $database) {
            config([
                "database.connections.{$database}.dump" => [
                    'exclude_tables' => $this->settings->exclude_tables,
                ],
            ]);
        }
    }
}
