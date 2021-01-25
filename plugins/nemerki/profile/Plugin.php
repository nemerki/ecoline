<?php namespace Nemerki\Profile;

use RainLab\User\Models\User as UserModel;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */

    public function boot()
    {


        UserModel::extend(function ($model) {

            /*  $model->hasOne['company'] = ['Nemerki\Smartjob\Models\Company'];

              $model->hasMany['cvs'] = ['Nemerki\Smartjob\Models\Cv'];*/
            /*
                           $model->hasMany['interviews'] = [
                               'Avirdigital\Jobustan\Models\Interview',
                               'key' => 'candidateuser_id',
                               'otherKey' => 'id',
                               'scope' => 'order',
                           ];

                           $model->hasMany['favoritjobs'] = [
                               'Avirdigital\Jobustan\Models\Favorite',
                               'key' => 'user_id',
                               'otherKey' => 'id',
                               'scope' => 'isJob',
                           ];
                           $model->hasMany['candidateConversations'] = [
                               'Avirdigital\Jobustan\Models\Conversation',
                               'key' => 'candidate_id',
                               'otherKey' => 'id',
                               'scope' => 'order',

                           ];
                           $model->hasMany['unread_messages'] = [
                               'Avirdigital\Jobustan\Models\Chat',
                               'key' => 'to',
                               'otherKey' => 'id',
                               'scope' => 'unreadMessage',
                           ];
                           $model->hasMany['employer_activejob'] = [
                               'Avirdigital\Jobustan\Models\Job',
                               'scope' => 'isAccepted',
                           ];
                           $model->hasMany['employer_preparing'] = [
                               'Avirdigital\Jobustan\Models\Job',
                               'scope' => 'isPreparing',
                           ];
                           $model->hasMany['employer_expired'] = [
                               'Avirdigital\Jobustan\Models\Job',
                               'scope' => 'isExpired',
                           ];
                           $model->hasMany['employer_deadline'] = [
                               'Avirdigital\Jobustan\Models\Job',
                               'scope' => 'isExpired',
                           ];

                           $model->attachOne['profile'] = ['System\Models\File'];*/

            /*  public function getFullNameAttribute()
              {

                  return $this->name . ' ' . $this->surname;
              }*/


            $model->addFillable([
                'app_id',
                'lat',
                'lng',
                'notes',
                'private_notes',
                'subscription',
                'discount_percentage',
                'credit_available',
                'pricelist_id',
                'qr'
            ]);

            $model->rules['app_id'] = 'nullable';
            $model->rules['lat'] = 'nullable';
            $model->rules['lng'] = 'nullable';
            $model->rules['notes'] = 'nullable';
            $model->rules['private_notes'] = 'nullable';
            $model->rules['subscription'] = 'nullable';
            $model->rules['discount_percentage'] = 'nullable';
            $model->rules['credit_available'] = 'nullable';
            $model->rules['pricelist_id'] = 'nullable';
            $model->rules['qr'] = 'nullable';


        });

        /*     UserController::extendFormFields(function ($form, $model, $context) {
                 $form->addTabFields([

                     'pricelist' => [
                         'label' => 'Pricelist',
                         'type' => 'relation',
                         'tab' => 'Profile',

                     ],
                 ]);
             });*/
    }


    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Avirdigital\Profile\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'avirdigital.profile.some_permission' => [
                'tab' => 'Profile',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'profile' => [
                'label' => 'Profile',
                'url' => Backend::url('avirdigital/profile/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['avirdigital.profile.*'],
                'order' => 500,
            ],
        ];
    }
}
