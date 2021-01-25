<?php

namespace Initbiz\Newsletter\Models;

use Backend\Models\ExportModel;

/**
 * Post Export Model
 */
class CheckboxesExport extends ExportModel
{
    public $table = 'initbiz_newsletter_checkboxes';

    public $fillable = ['required', 'name', 'slug', 'text'];

    public function exportData($columns, $sessionKey = null)
    {
        $checkboxes = Checkbox::all();
        $checkboxes->each(function ($checkbox) use ($columns) {
            if (!$checkbox->required) {
                $checkbox->required = 0;
            } else {
                $checkbox->required = 1;
            }
            //TODO: check if necessary
            $checkbox->addVisible($columns);
        });

        return $checkboxes->toArray();
    }
}
