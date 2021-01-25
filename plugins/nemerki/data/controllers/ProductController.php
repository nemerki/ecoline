<?php namespace Nemerki\Data\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class ProductController extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController',        'Backend\Behaviors\ReorderController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'product' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Nemerki.Data', 'main-menu-data', 'side-menu-product');
    }
}
