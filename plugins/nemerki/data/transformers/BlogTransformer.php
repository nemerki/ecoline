<?php namespace Nemerki\Data\Transformers;

use Nemerki\API\Classes\Transformer;
use Nemerki\Data\Models\Blog;


/**
 * BlogTransformer for Nemerki\Data\Model\Blog
 */
class BlogTransformer extends Transformer
{
    // Related transformer that can be included
    public $availableIncludes = [

    ];

    // Related transformer that will be included by default
    public $defaultIncludes = [

    ];

    public function data(Blog $model)
    {
        $cover = [
            'cover' => [370, 370],
        ];
        $img = [
            'detail' => ['auto', 370],
        ];
        return [
            'id' => (int)$model->id,
            'title' => $model->title,
            'slug' => $model->slug,
            'content' => $model->content,
            'cover' => $this->img($model->cover, $cover),
            'img' => $this->img($model->img, $img),
        ];
    }
}
