<?php

namespace Nemerki\Data\ApiControllers;


use Nemerki\API\Classes\ApiController;
use Nemerki\Data\Models\Blog;
use Nemerki\Data\Models\Selectable;
use Nemerki\Data\Transformers\BlogTransformer;
use Nemerki\Data\Transformers\CategoryTransformer;
use RainLab\Translate\Classes\Translator;

class Products extends ApiController
{

    public function index()
    {


        Translator::instance()->setLocale(request()->header('locale') ? request()->header('locale') : 'az', true);


        $model = Selectable::where('is_active', 1)->where('type_id', 1)->get();




        return $this->respondWithCollection($model, new CategoryTransformer());
    }



}

