<?php

namespace Initbiz\Newsletter\Controllers;

use Lang;
use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use Initbiz\Newsletter\Models\Subscriber;

class Subscribers extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ImportExportController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $importExportConfig = 'config_import_export.yaml';


    public $requiredPermissions = ['initbiz.newsletter.subscribers'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Initbiz.Newsletter', 'newsletter', 'subscribers');
    }

    public function onRemoveSubscribers()
    {
        if (($checkedId = post('checked')) && is_array($checkedId) && count($checkedId)) {
            $subscribers = Subscriber::get();
            foreach ($checkedId as $subscriberId) {
                foreach ($subscribers as $subscriber) {
                    if ($subscriber->id !== (int) $subscriberId)
                        continue;
                    $subscriber->delete();
                    Flash::success(Lang::get('initbiz.newsletter::lang.flash.deleted'));
                }
            }
        }

        return $this->listRefresh();
    }
}
