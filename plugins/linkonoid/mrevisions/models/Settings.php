<?php namespace Linkonoid\MRevisions\Models;

use Lang;
use Yaml;
use Model;
use Route;
use Flash;
use Event;
use File as FileHelper;
use October\Rain\Filesystem\Zip;
use Cms\Classes\Theme;
use System\Classes\PluginManager;
use Backend\Models\User as BackendUser;

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com)
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

class Settings extends Model
{

    public $implement = [
        '@System.Behaviors.SettingsModel',
    ];

    public $settingsCode = 'linkonoid_mrevisions_settings';
    public $settingsFields = 'fields.yaml';

	public $attachOne = [
        'import_export' => \System\Models\File::class
    ];

    /**
     * Validation rules
     */
    public $rules = [];

    public function __construct() {
        parent::__construct();
    }

    public function initSettingsData()
    {
        $this->history = empty($this->history) ? 'revision_history' : $this->history;
    }

    public function getBackendUserOptions()
    {
    	return BackendUser::lists('login','login');
    }

    public function getModelSelectOptions($key,$data)
    {
        $options = [
            'custom' => ' -- Your custom Model -- ',
	    	'\Cms\Classes\Page' => 'CMS->Page',
	 		'\Cms\Classes\Partial' => 'CMS->Partial',
	        '\Cms\Classes\Layout' => 'CMS->Layout',
	        '\Cms\Classes\Content' => 'CMS->Content',
	        '\Cms\Classes\Asset' => 'CMS->Asset'
        ];

        if (class_exists('\Rainlab\Pages\Classes\Page'))
        $options += [
	        '\RainLab\Pages\Classes\Page' => 'Rainlab.Pages->Page',
	        '\RainLab\Pages\Classes\Menu' => 'Rainlab.Pages->Menu',
	        '\RainLab\Pages\Classes\Content' => 'Rainlab.Pages->Content'
        ];

        foreach (PluginManager::instance()->getPlugins() as $code => $plugin) {
            $modelsPath = PluginManager::instance()->getPluginPath($code) . '/models';
            if (PluginManager::instance()->exists($code) && is_dir($modelsPath)) {
                $models = $models2 = scandir($modelsPath);
                foreach ($models as $modelFile){

                    $nameSpaceModel = explode('.',$code);
                    if (is_array($nameSpaceModel)) $nameSpaceModel = array_map(function ($item) {
				        return ucwords(trim($item));
			    	}, $nameSpaceModel);

                	if(!is_dir($modelsPath . '/' . $modelFile) && !in_array($modelFile, ['.php'])){
                		$modelFile = explode('.',$modelFile);
                		$modelFile = is_array($modelFile) ? trim($modelFile[0]) : '';
                    	$options['\\'.implode('\\',$nameSpaceModel).'\\Models\\'.$modelFile] = $nameSpaceModel[0].'.'.e(trans($plugin->pluginDetails()['name'])).'->'.$modelFile;
                	}
                }
            }
        }

        return $options;
    }

    public function getRevisionablesOptions($key,$data)
    {
        $options = [];

        $modelName = !empty($data->modelSelect) ? (($data->modelSelect !== 'custom') ? $data->modelSelect : (!empty($data->modelCustom) ? $data->modelCustom : null)) : null;

        if (!empty($modelName))
        if (class_exists($modelName))
        {
	        if ($modelName == '\Cms\Classes\Asset'){
	        	$model = new $modelName(Theme::getActiveTheme());
	        } else $model = new $modelName();

	        if ($model->methodExists('getConnection'))
	        {
		        $fields = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
		        foreach  ($fields as $value)
		        $options[$value] = $value;
			}
		}

    	return $options;
    }


	public function filterFields($fields, $context = null)
	{
       if ($this->revisionable_trigger == 1)
            $fields->{'revisionable_array'}->hidden = false;
       if (in_array($this->modelSelect,['\Cms\Classes\Page','\Cms\Classes\Partial','\Cms\Classes\Layout','\Cms\Classes\Content','\Cms\Classes\Asset','\RainLab\Pages\Classes\Page','\RainLab\Pages\Classes\Menu','\RainLab\Pages\Classes\Content']))
       		$fields->{'revisionable_array'}->hidden = true;
       if (!empty($this->modelCustom) && $this->modelSelect == 'custom')
       {
	       if (in_array($this->modelCustom,['\Cms\Classes\Page','\Cms\Classes\Partial','\Cms\Classes\Layout','\Cms\Classes\Content','\Cms\Classes\Asset','\RainLab\Pages\Classes\Page','\RainLab\Pages\Classes\Menu','\RainLab\Pages\Classes\Content']))
	       {
	       		$fields->{'revisionable_array'}->hidden = true;
	       } else  $fields->{'revisionable_array'}->hidden = false;
	   }
	}

    public function getRevisionableTriggerOptions($key,$data)
    {
	    $options = [0];

        if (!empty($data->modelSelect))
        if (!empty($data->modelCustom) && $data->modelSelect == 'custom'){
        	if (class_exists($data->modelCustom)){
	        	if (in_array($data->modelCustom,['\Cms\Classes\Page','\Cms\Classes\Partial','\Cms\Classes\Layout','\Cms\Classes\Content','\Cms\Classes\Asset','\RainLab\Pages\Classes\Page','\RainLab\Pages\Classes\Menu','\RainLab\Pages\Classes\Content']))
        			return $options;
                return [1];
        	}
        } else {
	        if (in_array($data->modelSelect,['\Cms\Classes\Page','\Cms\Classes\Partial','\Cms\Classes\Layout','\Cms\Classes\Content','\Cms\Classes\Asset','\RainLab\Pages\Classes\Page','\RainLab\Pages\Classes\Menu','\RainLab\Pages\Classes\Content']))
        		return $options;
            return [1];
        }

    	return $options;
    }

    public function getModelTabOptions($key,$data)
    {
        //if (!empty($data->modelSelect))
	    //if (in_array($data->modelSelect,['\Cms\Classes\Page','\Cms\Classes\Partial','\Cms\Classes\Layout','\Cms\Classes\Content','\Cms\Classes\Asset','\RainLab\Pages\Classes\Page','\RainLab\Pages\Classes\Menu','\RainLab\Pages\Classes\Content']))
        //	return ['primary'=>'Primary Tab','secondary'=>'Secondary Tab','outside'=>'Outside Tab'];

    	return ['secondary'=>'Secondary Tab','primary'=>'Primary Tab','outside'=>'Outside Tab'];
    }

    public function onFileDownload(){

    	$exportClass = explode('\\',get_class($this));

        $path = 'plugins/'.strtolower($exportClass[0].'/'.$exportClass[1]).'/impexp/';
        $localfilename = strtolower($exportClass[0]).'_'.strtolower($exportClass[1]).'_save_'. date('Y-m-d');
        $filename = $path.$localfilename;

        $filename_yaml = sprintf('%s.yaml', $filename);
        $localfilename_yaml = sprintf('%s.yaml', $localfilename);
    	$filename_zip = sprintf('%s.zip', $filename);

        $data = Settings::getSettingsRecord();

        unset($data->attributes['id']);

        $file_data = Yaml::render($data->attributes);

		FileHelper::put($filename_yaml, $file_data);
        Zip::make($filename_zip, $filename_yaml, ['recursive' => false]);

        if(file_exists($filename_zip) === TRUE)
        {
        	if(file_exists($filename_yaml) === TRUE) FileHelper::delete($filename_yaml);
            return response()->download($filename_zip)->deleteFileAfterSend(true);
        } else Flash::error('Error!');
	}


    public function getAccessOptions()
    {
    	return \Backend\Models\UserRole::lists('name','name');
    }

}