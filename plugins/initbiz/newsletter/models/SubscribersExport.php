<?php

namespace Initbiz\Newsletter\Models;

use Backend\Models\ExportModel;
use Initbiz\Newsletter\Models\Subscriber;

/**
 * Post Export Model
 */
class SubscribersExport extends ExportModel
{
    public $table = 'initbiz_newsletter_subscribers';

    public $fillable = ['email', 'confirmed', 'token'];

    public function exportData($columns, $sessionKey = null)
    {
        $subscribers = Subscriber::all();
        $subscribers->each(function ($subscriber) use ($columns) {
            $subscriber->addVisible($columns);
        });
        return $subscribers->toArray();
    }
}
