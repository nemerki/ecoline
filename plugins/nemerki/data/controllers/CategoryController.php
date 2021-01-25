<?php namespace Nemerki\Data\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use October\Rain\Support\Facades\Config;

class CategoryController extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'category'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Nemerki.Data', 'main-menu-data', 'side-menu-category');
    }

    public function listExtendQuery($query)
    {
        $query->where('type_id', Config::get('constants.selectable.category'));
    }

    public function formBeforeSave($model)
    {
        $model->type_id = Config::get('constants.selectable.category');
    }

    public function reorderExtendQuery($query)
    {
        $query->where('type_id', Config::get('constants.selectable.category'));
    }
}
