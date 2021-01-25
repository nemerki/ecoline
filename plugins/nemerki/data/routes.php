<?php


Route::group([
    'prefix' => 'api/v1/data',
    'namespace' => 'Nemerki\Data\ApiControllers',
    'middleware' => 'cors'
], function () {

    Route::get('setting', 'Settings@index');
    Route::get('slider', 'Sliders@index');
    Route::get('page/{slug}', 'Pages@show');

    Route::get('blog', 'Blogs@index');
    Route::get('blog/latest', 'Blogs@latest');
    Route::get('blog/detail/{slug}', 'Blogs@show');

    Route::get('category', 'Categories@index');
    http://ecoline.local/api/v1/data/category?include=products


});
