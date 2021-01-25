<?php

namespace Renatio\BackupManager\Controllers;

use Backend\Behaviors\ListController;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use October\Rain\Support\Facades\Flash;
use Renatio\BackupManager\Classes\SystemRequirements;
use Renatio\BackupManager\Models\Backup;

class Backups extends Controller
{

    public $requiredPermissions = ['renatio.backupmanager.access_backups'];

    public $implement = [
        ListController::class,
    ];

    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Renatio.BackupManager', 'backupmanager', 'backups');
    }

    public function index()
    {
        $this->vars['issues'] = (new SystemRequirements)->check();

        $this->asExtension('ListController')->index();
    }

    public function onCreate()
    {
        $result = Artisan::call('backup:run', post('only_db') ? ['--only-db' => true] : []);

        Storage::put('backup.log', Artisan::output());

        $result === 0
            ? Flash::success(e(trans('renatio.backupmanager::lang.backup.success')))
            : Flash::error(e(trans('renatio.backupmanager::lang.backup.failed')));

        return redirect()->refresh();
    }

    public function onClean()
    {
        $result = Artisan::call('backup:clean');

        Storage::put('backup.log', Artisan::output());

        $result === 0
            ? Flash::success(e(trans('renatio.backupmanager::lang.clean.success')))
            : Flash::error(e(trans('renatio.backupmanager::lang.clean.failed')));

        return $this->listRefresh();
    }

    public function onDeleteBackup()
    {
        $backup = Backup::find(post('id'));

        $backup->delete();

        Flash::success(e(trans('backend::lang.list.delete_selected_success')));

        return $this->listRefresh();
    }

    public function onPreviewLog()
    {
        return $this->makePartial('log', [
            'log' => Storage::exists('backup.log')
                ? Storage::get('backup.log')
                : e(trans('renatio.backupmanager::lang.log.empty')),
        ]);
    }

    public function download($id)
    {
        $backup = Backup::findOrFail($id);

        return $backup->download();
    }
}
