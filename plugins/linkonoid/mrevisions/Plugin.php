<?php namespace Linkonoid\MRevisions;

use App;
use Lang;
use Yaml;
use Event;
use Model;
use Route;
use Flash;
use Backend;
use Redirect;
use Cms\Classes\Theme;
use System\Models\Revision;
//use October\Rain\Database\Models\Revision as Revision;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Linkonoid\MRevisions\Models\Settings;

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

class Plugin extends PluginBase
{

    public $requiredPermissions = ['linkonoid.mrevisions.access_settings'];

    public function registerPermissions()
    {
		return [
            'linkonoid.mrevisions.access_restore'  => [
                'tab'   => 'Multi Revisions',
                'label' => 'Multi Revisions restore enable',
            ],
			'linkonoid.mrevisions.access_settings'  => [
                'tab'   => 'Multi Revisions',
                'label' => 'Multi Revisions access settings',
            ]
        ];
	}

    public $revisionableSettings = [];

    private $instanceArr = [
    	'Cms\Classes\Page' => 'pages/',
 		'Cms\Classes\Partial' => 'partials/',
        'Cms\Classes\Layout' => 'layouts/',
        'Cms\Classes\Content' => 'content/',
        'Cms\Classes\Asset' => 'assets/',
        'RainLab\Pages\Classes\Page' => 'content/static-pages/',
        'RainLab\Pages\Classes\Menu' => 'meta/menus/',
        'RainLab\Pages\Classes\Content' => 'content/'
    ];


	public function register()
    {

        Event::listen('backend.form.extendFields', function ($widget) {

			if (empty($this->revisionableSetting)) {
                $this->revisionableSettings = $this->getRevisionableSettings();
			}

            foreach ($this->revisionableSettings as $revisionableVars)
            {
				if (empty($revisionableVars)) continue;
				if (empty($revisionableVars['model'])) continue;
				if (!$widget->model instanceof $revisionableVars['model']) continue;

                if ($cmsPage = isset($this->instanceArr[get_class($widget->model)])) {
		            if (\System\Models\LogSetting::instance()->log_theme == 0) continue;
		            if (!($theme = Theme::getEditTheme())) {
	                	throw new ApplicationException(Lang::get('cms::lang.theme.edit.not_found'));
	            	}
		        }

	 		    if (!$widget->isNested) {


			 		$revisions = [];
			        $nameClass = $widget->model ? get_class($widget->model) : null;

			        if($cmsPage)
			        {
			            $filename = $this->instanceArr[$nameClass] . $widget->model->fileName;
			            $history = \Cms\Models\ThemeLog::where('template', $filename)->where('type', '<>', \Cms\Models\ThemeLog::TYPE_CREATE)->with('user')->orderBy('created_at', 'desc')->first();
			        } else $history = Revision::where('revisionable_id',$widget->model->id)->first();

			        if (!$history) return;

                    if ($revisionableVars['quickButtonEnabled'])
                    {
		                $widget->addFields(
		                     [
		                         'restore' => [
		                             'type' 	=> 'Linkonoid\MRevisions\FormWidgets\Restore',
		                             'stretch' => true,
	                                 'cssClass' => 'collapse-visible',
		                             'attributes' => ['partial' => 'button', 'cmsPage' => $cmsPage, 'instanceArr' => $this->instanceArr]
		                         ]

		                     ],
		                     'outside' //'secondary,''primary'
		                );
					}

	                $widget->addFields(
	                     [
	                         'history' => [
	                             'type' 	=> 'Linkonoid\MRevisions\FormWidgets\Restore',
	                             'tab'     => 'Changes history',
	                             'span'    => 'full',
	                             'stretch' => true,
	                             'attributes' => ['partial' => 'list', 'cmsPage' => $cmsPage, 'instanceArr' => $this->instanceArr]
	                         ]
	                     ],
	                     !empty($revisionableVars['modelTab']) ? $revisionableVars['modelTab'] : ($cmsPage ? 'secondary' : 'primary')
	                );
				}
			}
        });

    	### exclude the revision folder from the CMS/Page sidebar
        Event::listen('cms.object.listInTheme', function ($cmsObject, $objectList) {
            if ($cmsObject instanceof \Cms\Classes\Page) {
                foreach ($objectList as $index => $page) {
                    if (dirname($page->fileName) === 'revisions') {
                        $objectList->forget($index);
                    }
                }
            }
        });

    }

    private function getRevisionableSettings()
    {
        $settings = Settings::instance();
        $models = $settings->get('models');
        $enabled = $settings->get('enabled');
        $user = $settings->get('user');
        $user = !empty($user) ? $user : 'Backend\Models\User';

        $revisionableSettings = [];

    	if (!empty($models) && !empty($enabled))
        if ($enabled)
		{
			foreach ($models as $keyModel => $itemModel)
			if (!empty($itemModel['enabled']))
			if ($itemModel['enabled'])
			{
                $rev_array = array_flatten(!empty($itemModel['revisionables']) ? $itemModel['revisionables'] : []);
                $rev_array = array_merge($rev_array, Yaml::parse(!empty($itemModel['revisionable_array']) ? $itemModel['revisionable_array'] : "[]"));
                $rev_array = array_unique($rev_array);

                $rev_count = count($rev_array);

		        $revisionableVars = [
		            'quickButtonEnabled' => !empty($itemModel['quickButtonEnabled']) ? $itemModel['quickButtonEnabled'] : false,
		            'revisionsEnabled' => !empty($itemModel['enabled']) ? $itemModel['enabled'] : false,
		        	'model' => ($itemModel['modelSelect'] != 'custom') ? $itemModel['modelSelect'] : (!empty($itemModel['modelCustom']) ? $itemModel['modelCustom'] : null),
		            'revisionable' => $rev_array,
		            'revisionableLimit' => !empty($itemModel['limit']) ? ($rev_count*$itemModel['limit']) : $rev_count*Settings::instance()->get('limit',999),
		            'modelUser' => $user,
		            'modelTab' => !empty($itemModel['modelTab']) ? $itemModel['modelTab'] : null
		        ];

		        $revisionableSettings[] = $revisionableVars;
			}
		}

        //$this->revisionableSettings = $revisionableSettings;

		return $revisionableSettings;
    }


	public function boot()
	{
		if (!App::runningInBackend()) return;

    	Route::any('backend/linkonoid/mrevisions/export', '\Linkonoid\MRevisions\Models\Settings@onFileDownload');

    	$this->revisionableSettings = $this->getRevisionableSettings();

        if (class_exists('\Cms\Controllers\Index'))
        \Cms\Controllers\Index::extend(function ($controller){
        	if(!$controller->methodExists('onRestore'))
        	$controller->implement[] = 'Linkonoid.MRevisions.Behaviors.RestoreBehavior';

		});

        if (class_exists('\RainLab\Pages\Controllers\Index'))
        \RainLab\Pages\Controllers\Index::extend(function ($controller){
        	if(!$controller->methodExists('onRestore'))
        	$controller->implement[] = 'Linkonoid.MRevisions.Behaviors.RestoreBehavior';
		});

        foreach ($this->revisionableSettings as $revisionableVars)
        {
        	if(!empty($revisionableVars['model']))
        	if (class_exists($revisionableVars['model']))
	        $revisionableVars['model']::extend(function ($model) use ($revisionableVars){

                $nameClass = get_class($model);

                if (!isset($this->instanceArr[$nameClass])) {
			        $model->implement[] = 'Linkonoid.MRevisions.Behaviors.RevisionsBehavior';
			        $model->addDynamicProperty('revisionableVars', $revisionableVars);
				}

			});
		}
	}

  /**
     * Register settings for this plugin.
     * These settings will be available on the settings page of October CMS.
     * @return array The available settings for this plugin.
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => Lang::get('linkonoid.mrevisions::lang.settings.menu_label'),
                'description' => Lang::get('linkonoid.mrevisions::lang.settings.menu_description'),
                'category'    => 'MRevisions',
                'icon'        => 'icon-table',
                'class'       => 'Linkonoid\MRevisions\Models\Settings',
                'order'       => 500,
                'keywords'    => 'MRevisions, Multi Revisions',
                'permissions' => ['linkonoid.mrevisions.access_settings']
            ]
        ];
    }
}
