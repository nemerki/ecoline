<?php

namespace Initbiz\Newsletter\Models;

use Model;
use Initbiz\Newsletter\Classes\Helpers;

class Subscriber extends Model
{

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_subscribers';

    protected $fillable = ['confirmed', 'email', 'token'];

    public $rules = [
        'email'   => 'required|email',
    ];
    public $belongsToMany = [
        'checkboxes' => [
            'Initbiz\Newsletter\Models\Checkbox',
            'table' => 'initbiz_newsletter_checkbox_subscriber',
        ]
    ];

    public function getTokenAttribute()
    {
        if ($this->exists && $this->attributes['token']) {
            return $this->attributes['token'];
        }

        return Helpers::generateToken();
    }
}
