<?php

namespace Renatio\BackupManager\Models;

use Illuminate\Support\Facades\File;
use October\Rain\Database\Model;
use Renatio\BackupManager\Classes\DownloadResponse;
use Renatio\BackupManager\Traits\ManageFiles;
use Spatie\Backup\BackupDestination\BackupDestination;

class Backup extends Model
{

    use ManageFiles;

    public $table = 'renatio_backupmanager_backups';

    protected $fillable = ['disk_name', 'file_path', 'filesystems', 'file_size'];

    public static function createModel(BackupDestination $backupDestination)
    {
        $backup = $backupDestination->newestBackup();

        return static::create([
            'disk_name' => basename($backup->path()),
            'file_path' => $backup->path(),
            'filesystems' => $backupDestination->diskName(),
            'file_size' => $backup->size(),
        ]);
    }

    public function getFileSizeAttribute($val)
    {
        return File::sizeToString($val);
    }

    public function afterDelete()
    {
        $this->deleteFile();
    }

    public function download()
    {
        return (new DownloadResponse($this))->create();
    }
}
