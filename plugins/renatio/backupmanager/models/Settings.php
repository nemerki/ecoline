<?php

namespace Renatio\BackupManager\Models;

use DB;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\Encryptable;
use October\Rain\Database\Traits\Validation;
use Spatie\DbDumper\Compressors\GzipCompressor;
use System\Behaviors\SettingsModel;

class Settings extends Model
{

    use Encryptable,
        Validation;

    public $implement = [SettingsModel::class];

    public $settingsCode = 'renatio_backupmanager_settings';

    public $settingsFields = 'fields.yaml';

    protected $encryptable = ['zip_password'];

    public $rules = [
        'zip_password' => 'required_with:zip_password_encryption',
    ];

    public function initSettingsData()
    {
        foreach ($this->getDefaultSettings() as $key => $setting) {
            $this->{$key} = $setting;
        }
    }

    public function getDefaultSettings()
    {
        return [
            'databases' => [
                config('database.default'),
            ],
            'exclude_tables' => ['backend_access_log', 'system_event_logs', 'system_request_logs'],
            'gzip_database_dump' => false,
            'follow_links' => false,
            'include' => [
            ],
            'exclude' => [
                ['path' => 'vendor'],
                ['path' => 'node_modules'],
            ],
            'backup_name' => config('app.name', 'backups'),
            'filename_prefix' => '',
            'disks' => ['local'],
            'keep_all' => 7,
            'keep_daily' => 16,
            'keep_weekly' => 8,
            'keep_monthly' => 4,
            'keep_yearly' => 2,
            'delete_oldest_when_mb' => 5000,
        ];
    }

    public function getIncludePaths()
    {
        if (empty($this->include)) {
            return [base_path()];
        }

        return collect($this->include)
            ->flatten()
            ->map(function ($path) {
                return base_path($path);
            })
            ->toArray();
    }

    public function getExcludePaths()
    {
        if (empty($this->exclude)) {
            return [];
        }

        return collect($this->exclude)
            ->flatten()
            ->map(function ($path) {
                return base_path($path);
            })
            ->toArray();
    }

    public function compression()
    {
        return $this->gzip_database_dump ? GzipCompressor::class : null;
    }

    public function getDatabasesOptions()
    {
        $keys = array_keys(config('database.connections'));

        return array_combine($keys, $keys);
    }

    public function getDisksOptions()
    {
        $keys = array_keys(config('filesystems.disks'));

        return array_combine($keys, $keys);
    }

    public function getSchedulerOptions()
    {
        return [
            '' => e(trans('renatio.backupmanager::lang.schedule.choose')),
            'everyFiveMinutes' => e(trans('renatio.backupmanager::lang.schedule.every_five_minutes')),
            'everyTenMinutes' => e(trans('renatio.backupmanager::lang.schedule.every_ten_minutes')),
            'everyThirtyMinutes' => e(trans('renatio.backupmanager::lang.schedule.every_thirty_minutes')),
            'hourly' => e(trans('renatio.backupmanager::lang.schedule.hourly')),
            'daily' => e(trans('renatio.backupmanager::lang.schedule.daily')),
            'weekly' => e(trans('renatio.backupmanager::lang.schedule.weekly')),
            'monthly' => e(trans('renatio.backupmanager::lang.schedule.monthly')),
            'yearly' => e(trans('renatio.backupmanager::lang.schedule.yearly')),
        ];
    }

    public function getExcludeTablesOptions()
    {
        return DB::connection()->getDoctrineSchemaManager()->listTableNames();
    }
}
