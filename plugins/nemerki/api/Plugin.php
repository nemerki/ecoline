<?php namespace Nemerki\Api;

use App;
use Config;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;

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
        // Register Cors
        App::register('\Barryvdh\Cors\ServiceProvider');

        // Add cors middleware
        app('router')->aliasMiddleware('cors', \Barryvdh\Cors\HandleCors::class);

    }

    public function register()
    {
        $this->registerConsoleCommand('nemerki.api.transformer', 'Nemerki\API\Console\CreateTransformer');
    }
}
