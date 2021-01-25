<?php

namespace Nemerki\Data\ApiControllers;


use Nemerki\API\Classes\ApiController;
use Nemerki\Data\Transformers\PageTransformer;
use RainLab\Pages\Classes\Page as StaticPage;
use RainLab\Translate\Classes\Translator;

class Pages extends ApiController
{
    public function show($slug)
    {

        Translator::instance()->setLocale(request()->header('locale') ? request()->header('locale') : 'az', true);

        $model = StaticPage::find($slug);

        return $this->respondWithItem($model, new PageTransformer());
    }

}

