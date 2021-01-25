<?php

namespace Initbiz\Newsletter\Controllers;

use Lang;
use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use Initbiz\Newsletter\Models\Checkbox;

class Checkboxes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ImportExportController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public $requiredPermissions = ['initbiz.newsletter.checkboxes'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Initbiz.Newsletter', 'newsletter', 'checkboxes');
    }

    public function onRemoveCheckboxes()
    {
        //TODO: Checkbox::beforeDelete do not run without two foreach
        if (($checkedId = post('checked')) && is_array($checkedId) && count($checkedId)) {
            $checkboxes = Checkbox::get();
            foreach ($checkedId as $checkboxId) {
                foreach ($checkboxes as $checkbox) {
                    if ($checkbox->id !== (int) $checkboxId)
                        continue;
                    $checkbox->delete();
                    Flash::success(Lang::get('initbiz.newsletter::lang.flash.deleted'));
                }
            }
        }

        return $this->listRefresh();
    }
}
