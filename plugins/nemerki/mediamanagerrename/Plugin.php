<?php namespace Nemerki\MediaManagerRename;

use Event;
use System\Classes\MediaLibrary;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Event::listen('media.file.upload', function ($widget, $filePath, $uploadedFile) {
            $original_name  = $uploadedFile->getClientOriginalName();
            $ext     = pathinfo($original_name, PATHINFO_EXTENSION);
            $original_name_no_ext = pathinfo($original_name, PATHINFO_FILENAME);
            $new_name = str_slug($original_name_no_ext, '-') ."_".date('YmdHis'). '.' . $ext;


            if ($new_name == $original_name) {
                return;
            }

            $newPath = str_replace($original_name, $new_name, $filePath);
            MediaLibrary::instance()->moveFile($filePath, $newPath);
        });
    }
}
