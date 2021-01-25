<?php

namespace Initbiz\Newsletter\Controllers;

use Lang;
use Mail;
use Flash;
use Exception;
use Validator;
use BackendMenu;
use Backend\Classes\Controller;
use Initbiz\Newsletter\Models\Checkbox;
use October\Rain\Exception\ValidationException;
use Initbiz\Newsletter\Models\Message as Message;

class Messages extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['initbiz.newsletter.messages'];

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        if (!empty($this->params)) {
            $message = Message::where('id', $this->params[0])->first();
            $this->vars['checked_checkboxes'] = $message->checkboxes->pluck('slug')->all();
        }

        $this->vars['checkboxes'] = Checkbox::all();

        BackendMenu::setContext('Initbiz.Newsletter', 'newsletter', 'messages');
    }

    public function onTest()
    {
        $user = $this->user;

        $data = post('Message');

        $rules = [
            'content' => 'required',
            'email_template' => 'required',
            'title' => 'required',
        ];

        $validation = Validator::make($data, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        try {
            $options = [
                'content' => $data['content'],
            ];

            Mail::send($data['email_template'], $options, function ($message) use ($user, $data) {
                $message->to($user->email, $user->full_name);
                $message->subject($data['title']);
            });

            Flash::success(trans('system::lang.mail_templates.test_success'));
        }
        catch (Exception $e) {
            Flash::error($e->getMessage());
        }
    }

    public function onRemoveMessages()
    {
        if (($checkedId = post('checked')) && is_array($checkedId) && count($checkedId)) {
            $messages = Message::get();
            foreach ($checkedId as $messageId) {
                foreach ($messages as $message) {
                    if ($message->id !== (int) $messageId)
                        continue;
                    $message->delete();
                    Flash::success(Lang::get('initbiz.newsletter::lang.flash.deleted'));
                }
            }
        }

        return $this->listRefresh();
    }
}
