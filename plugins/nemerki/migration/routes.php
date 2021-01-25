<?php
Route::group([
    'prefix' => 'migration',
    'namespace' => 'Nemerki\Migration\MigrationControllers',

], function () {

    Route::get('product', 'Products@index');
    Route::get('customer', 'Customers@index');

});

