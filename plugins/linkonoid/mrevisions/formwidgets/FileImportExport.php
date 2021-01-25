<?php namespace Linkonoid\MRevisions\FormWidgets;

use Url;
use Yaml;
use Input;
use Flash;
use Response;
use Validator;
use File as FileHelper;
use ValidationException;
use ApplicationException;
use Backend\Classes\FormField;
use October\Rain\Filesystem\Zip;
//use Backend\Classes\FormWidgetBase;
use Linkonoid\MRevisions\Models\Settings;

/**
 * @package linkonoid\mrevisions
 * @idea and sponsored by Mightyhaggis (https://mightyhaggis.com) 
 * @coding Linkonoid (https://github.com/linkonoid, https://linkonoid.com)
 * @uses modal Dialog from Toolbox plugin by Prismify (https://github.com/prismify/oc-toolbox-plugin)
**/

class FileImportExport extends \Backend\FormWidgets\FileUpload
{
    public function onUpload()
    {
        try {
            if (!Input::hasFile('file_data')) {
                throw new ApplicationException('File missing from request');
            }

            $fileModel = $this->getRelationModel();
            $uploadedFile = Input::file('file_data');

            $validationRules = ['max:'.$fileModel::getMaxFilesize()];
            if ($fileTypes = $this->getAcceptedFileTypes()) {
                $validationRules[] = 'extensions:'.$fileTypes;
            }

            if ($this->mimeTypes) {
                $validationRules[] = 'mimes:'.$this->mimeTypes;
            }

            $validation = Validator::make(
                ['file_data' => $uploadedFile],
                ['file_data' => $validationRules]
            );

            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            if (!$uploadedFile->isValid()) {
                throw new ApplicationException('File is not valid');
            } else {
                $name = $uploadedFile->getClientOriginalName();
                $extension = $uploadedFile->getClientOriginalExtension();

                $path = 'plugins/linkonoid/mrevisions/impexp/';

                //Move the uploaded to work folder
                $moved = Input::file('file_data')->move($path,$name);

                if($extension=='yaml')
                {
                    $filename_yaml = $path.$name;
                    $filename_yaml = trim(explode('(',$filename_yaml)[0]);

                } elseif($extension=='zip') {

                    $filename_zip = $path.$name;

                    Zip::extract($filename_zip, $path);

                    $filename_zip_new = trim(explode('(',$filename_zip)[0]);
                    $fileNameNoExt = explode('.',$filename_zip_new)[0];
                    $filename_yaml = sprintf('%s.yaml', $fileNameNoExt);

                    if(file_exists($filename_zip) === TRUE) FileHelper::delete($filename_zip);
                } else {
                	Flash::error(Lang::get('inkonoid.mrevisions::lang.plugin.error'));
                }
            }

            $file_data = Yaml::parseFile($filename_yaml);
            foreach ($file_data as $key => $value)
            	Settings::set($key,$value);

            if(file_exists($filename_yaml) === TRUE) FileHelper::delete($filename_yaml);

            $result = [
                'path' => $name,
                'reload' => true
            ];

            $response = Response::make($result, 200);
        }
        catch (Exception $ex) {
            $response = Response::make($ex->getMessage(), 400);
        }

        return $response;
    }

    protected function loadAssets()
    {
        $this->addCss('css/fileupload.css', 'core');
        $this->addJs('js/fileupload.js', [
            'build' => 'core',
            'cache'  => 'false'
        ]);
    }

    public function getExportFilePath()
	{
	    return Url::to('backend/linkonoid/mrevisions/export');
	}

    public function getSaveValue($value)
    {
        return FormField::NO_SAVE_DATA;
    }
}
