<?php

namespace Renatio\BackupManager\Classes;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadResponse
{

    protected $backup;

    public function __construct($backup)
    {
        $this->backup = $backup;
    }

    public function create()
    {
        $response = new StreamedResponse(function () {
            $this->readStream();
        });

        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $this->backup->disk_name
        ));

        return $response;
    }

    protected function readStream()
    {
        foreach ($this->backup->disks() as $disk) {
            if ($this->backup->exists($disk)) {
                $stream = Storage::disk($disk)->getDriver()->readStream($this->backup->file_path);

                fpassthru($stream);
            }
        }
    }
}
