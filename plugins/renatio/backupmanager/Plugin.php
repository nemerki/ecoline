<?php

namespace Renatio\BackupManager;

use Backend\Facades\Backend;
use Renatio\BackupManager\Classes\BackupConfig;
use Renatio\BackupManager\Classes\Schedule;
use Renatio\BackupManager\Models\Settings;
use Renatio\BackupManager\Providers\BackupServiceProvider;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public $elevated = true;

    public function pluginDetails()
    {
        return [
            'name' => 'renatio.backupmanager::lang.plugin.name',
            'description' => 'renatio.backupmanager::lang.plugin.description',
            'author' => 'Renatio',
            'icon' => 'icon-database',
        ];
    }

    public function boot()
    {
        $this->app->register(BackupServiceProvider::class);

        (new BackupConfig)->init();
    }

    public function registerNavigation()
    {
        return [
            'backupmanager' => [
                'label' => 'renatio.backupmanager::lang.navigation.backups',
                'url' => Backend::url('renatio/backupmanager/backups'),
                'icon' => 'icon-database',
                'permissions' => ['renatio.backupmanager.access_backups'],
                'order' => 500,
            ],
        ];
    }

    public function registerPermissions()
    {
        return [
            'renatio.backupmanager.access_backups' => [
                'label' => 'renatio.backupmanager::lang.permissions.access_backups',
                'tab' => 'renatio.backupmanager::lang.permissions.tab',
            ],
            'renatio.backupmanager.access_settings' => [
                'label' => 'renatio.backupmanager::lang.permissions.access_settings',
                'tab' => 'renatio.backupmanager::lang.permissions.tab',
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label' => 'renatio.backupmanager::lang.settings.label',
                'description' => 'renatio.backupmanager::lang.settings.description',
                'category' => 'renatio.backupmanager::lang.settings.category',
                'icon' => 'icon-database',
                'class' => Settings::class,
                'order' => 500,
                'keywords' => 'backup',
                'permissions' => ['renatio.backupmanager.access_settings'],
            ],
        ];
    }

    public function registerSchedule($schedule)
    {
        (new Schedule($schedule))
            ->databaseBackup()
            ->appBackup()
            ->cleanOldBackups();
    }

    public function registerMailTemplates()
    {
        return [
            'renatio.backupmanager::mail.notification',
        ];
    }
}
