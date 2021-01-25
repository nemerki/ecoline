<?php namespace Nemerki\Data\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\Backend;
use BackendMenu;

class DataController extends Controller
{
    public $implement = [    ];

    public $requiredPermissions = [
        'data'
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Nemerki.Data', 'main-menu-data');
    }
    public function index()
    {
        return Backend::redirect('nemerki/data/datacontroller/preview');
    }

    public function preview()
    {

    }
}
