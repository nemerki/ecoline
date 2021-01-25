<?php namespace Nemerki\Data\Transformers;

use Nemerki\API\Classes\Transformer;
use Nemerki\Data\Models\Slider;


/**
 * SliderTransformer for Nemerki\Data\Model\Slider
 */
class SliderTransformer extends Transformer
{
    // Related transformer that can be included
    public $availableIncludes = [

    ];

    // Related transformer that will be included by default
    public $defaultIncludes = [

    ];

    public function data(Slider $model)
    {

        $image = [
            'slider' => ['auto', 710],
        ];
        return [
            'id' => (int)$model->id,
            'title' => $model->title,
            'url' => $model->url,
            'img' => $this->img($model->img, $image),

        ];
    }
}
