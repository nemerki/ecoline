<?php namespace Nemerki\Data\Transformers;

use Nemerki\API\Classes\Transformer;
use Nemerki\Data\Models\Selectable;


/**
 * CategoryTransformer for Nemerki\Data\Models\Category
 */
class CategoryTransformer extends Transformer
{
    // Related transformer that can be included
    public $availableIncludes = [
        'products'
    ];

    // Related transformer that will be included by default
    public $defaultIncludes = [

    ];

    public function data(Selectable $model)
    {
        return [
            'id' => (int)$model->id,
            'name' => $model->name
        ];
    }

    public function includeProducts(Selectable $model)
    {
        return $this->collection($model->products, new ProductTransformer());
    }
}
