<?php namespace Nemerki\Data\Models;

use Model;
use October\Rain\Support\Facades\Config;

/**
 * Model
 */
class Selectable extends Model
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
    public $table = 'nemerki_data_selectables';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];


    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = ['name'];

    public $hasMany = [
        'products' => [
            'Nemerki\Data\Models\Product',
            'key' => 'category_id',
            'otherKey' => 'id',
            'conditions' => 'is_active = 1'
        ],
    ];

    public function scopeCategory($query)
    {
        return $query->where('type_id', Config::get('constants.type.category'))->orderBy('sort_order', 'asc');
    }
}
