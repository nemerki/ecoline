<?php namespace Nemerki\Data\Transformers;

use RainLab\Pages\Classes\Page;
use Nemerki\API\Classes\Transformer;


/**
 * PageTransformer for RainLab\Pages\Classes\Page
 */
class PageTransformer extends Transformer
{
    // Related transformer that can be included
    public $availableIncludes = [

    ];

    // Related transformer that will be included by default
    public $defaultIncludes = [

    ];

    public function data(Page $model)
    {
        return [
            'viewBag'    =>  $model->viewBag,
            'settings'    =>  $model->settings,
            'markup'    =>  $model->markup,
        ];
    }
}
