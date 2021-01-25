<?php namespace Nemerki\Data\Models;

use Model;
use October\Rain\Support\Facades\Config;

/**
 * Model
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Sortable;

    use \October\Rain\Database\Traits\Sluggable;

    protected $slugs = ['slug' => 'name'];
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'nemerki_data_products';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
    public $belongsTo = [
        'category' => [
            'scope' => 'category',
            'Nemerki\Data\Models\Selectable',
            'key' => 'category_id',
            'otherKey' => 'app_id'],

    ];



}
