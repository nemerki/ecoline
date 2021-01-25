<?php namespace Nemerki\Data\Models;

use Model;

/**
 * Model
 */
class Blog extends Model
{
    use \October\Rain\Database\Traits\Validation;

    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Sortable;

    use \October\Rain\Database\Traits\Sluggable;

    protected $slugs = ['slug' => 'title'];
    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'nemerki_data_blogs';


    /**
     * @var array Validation rules
     */
    public $rules = [
        'title' => 'required',
        'cover' => 'required',
        'content' => 'required'
    ];


    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = ['title', 'img', 'cover', 'content'];
}
