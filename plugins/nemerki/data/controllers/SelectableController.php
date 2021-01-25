<?php namespace Nemerki\Data\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\Backend;
use BackendMenu;

class SelectableController extends Controller
{
    public $implement = [    ];

    public $requiredPermissions = [
        'selectabel'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Nemerki.Data', 'main-menu-selectable');
    }
    public function index()
    {
        return Backend::redirect('nemerki/data/selectablecontroller/preview');
    }

    public function preview()
    {

    }
}
