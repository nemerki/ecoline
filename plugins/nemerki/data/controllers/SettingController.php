<?php namespace Nemerki\Data\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class SettingController extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'setting' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Nemerki.Data', 'main-menu-data', 'side-menu-setting');
    }
}
