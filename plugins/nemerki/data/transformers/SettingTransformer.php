<?php namespace Nemerki\Data\Transformers;

use Nemerki\Data\Models\Setting;
use Nemerki\API\Classes\Transformer;


/**
 * SettingTransformer for Nemerki\Data\Models\Setting
 */
class SettingTransformer extends Transformer
{
    // Related transformer that can be included
    public $availableIncludes = [

    ];

    // Related transformer that will be included by default
    public $defaultIncludes = [

    ];

    public function data(Setting $model)
    {
        return [
            'header_logo'    => $model->header_logo,
            'header_open'    => $model->header_open,
            'header_phone'    => $model->header_phone,
            'footer_logo'    => $model->footer_logo,
            'social'    => $model->social,
            'copyright'    => $model->copyright,
            'map_location'    => $model->map_location,
        ];
    }
}
