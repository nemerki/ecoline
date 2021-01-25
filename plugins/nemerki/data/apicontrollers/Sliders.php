<?php

namespace Nemerki\Data\ApiControllers;


use Nemerki\API\Classes\ApiController;
use Nemerki\Data\Models\Slider;
use Nemerki\Data\Transformers\SliderTransformer;
use RainLab\Translate\Classes\Translator;

class Sliders extends ApiController
{
    public function index()
    {

        Translator::instance()->setLocale(request()->header('locale') ? request()->header('locale') : 'az', true);


        $model = Slider::where('is_active', 1)->orderBy('sort_order', 'DESC')->take(10)->get();


        return $this->respondWithCollection($model, new SliderTransformer());
    }

}

