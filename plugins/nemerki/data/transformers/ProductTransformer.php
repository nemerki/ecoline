<?php namespace Nemerki\Data\Transformers;

use Nemerki\Data\Models\Product;
use Nemerki\API\Classes\Transformer;


/**
 * ProductTransformer for Nemerki\Data\Models\Product
 */
class ProductTransformer extends Transformer
{
    // Related transformer that can be included
    public $availableIncludes = [

    ];

    // Related transformer that will be included by default
    public $defaultIncludes = [

    ];

    public function data(Product $model)
    {
        $img = [
            'cover' => ['auto', 270],
            'order' => [170, 195],
        ];
        return [
            'id'    => (int) $model->id,
            'name'    =>  $model->name,
            'slug'    =>  $model->slug,
            'price'    =>  $model->price,
            'price_express'    =>  $model->price_express,
            'pieces'    =>  $model->pieces,
            'app_id'    =>  $model->app_id,
            'img' => $this->img($model->img, $img),
        ];
    }
}
