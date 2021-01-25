<?php

namespace Nemerki\Data\ApiControllers;


use Nemerki\API\Classes\ApiController;
use Nemerki\Data\Models\Blog;
use Nemerki\Data\Transformers\BlogTransformer;
use RainLab\Translate\Classes\Translator;

class Blogs extends ApiController
{

    public function index()
    {


        Translator::instance()->setLocale(request()->header('locale') ? request()->header('locale') : 'az', true);


        $model = Blog::where('is_active', 1)->orderBy('sort_order', 'DESC');


        $model = $model->paginate(request()->header('number') ? request()->header('number') : 20, request()->header('page') ? request()->header('page') : 1);


        return $this->respondWithPaginator($model, new BlogTransformer());
    }

    public function latest()
    {

        Translator::instance()->setLocale(request()->header('locale') ? request()->header('locale') : 'az', true);


        $model = Blog::where('is_active', 1)->where('is_home', 1)->orderBy('sort_order', 'DESC')->take(3)->get();


        return $this->respondWithCollection($model, new BlogTransformer());
    }

    public function show($slug)
    {


        Translator::instance()->setLocale(request()->header('locale') ? request()->header('locale') : 'az', true);

        $data = Blog::where('slug', $slug)->first();

        return $this->respondwithItem($data, new BlogTransformer());

    }

}

