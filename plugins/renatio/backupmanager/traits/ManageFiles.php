<?php

namespace Renatio\BackupManager\Traits;

use Illuminate\Support\Facades\Storage;

trait ManageFiles
{

    public function deleteFile()
    {
        foreach ($this->disks() as $disk) {
            $this->deleteFromDisk($disk);
        }
    }

    public function disks()
    {
        return explode(', ', $this->filesystems);
    }

    public function exists($disk)
    {
        return Storage::disk($disk)->exists($this->file_path);
    }

    protected function deleteFromDisk($disk)
    {
        if ($this->exists($disk)) {
            Storage::disk($disk)->delete($this->file_path);
        }
    }
}
