<?php

namespace Nemerki\Data\ApiControllers;


use Nemerki\API\Classes\ApiController;
use Nemerki\Data\Models\Setting;
use Nemerki\Data\Transformers\SettingTransformer;
use RainLab\Translate\Classes\Translator;

class Settings extends ApiController
{
    public function index()
    {

        Translator::instance()->setLocale(request()->header('locale') ? request()->header('locale') : 'az', true);


        $model = Setting::find(1);


        return $this->respondWithItem($model, new SettingTransformer());
    }

}

