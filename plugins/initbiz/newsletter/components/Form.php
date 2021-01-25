<?php

namespace Initbiz\Newsletter\Components;

use Db;
use Lang;
use Validator;
use Exception;
use ValidationException;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Classes\Helpers;
use Initbiz\Newsletter\Classes\SendEmail;
use Initbiz\Newsletter\Models\Subscriber;
use Initbiz\Newsletter\Classes\SubscriptionException;

class Form extends ComponentBase
{
    protected $subscriber;

    public function componentDetails()
    {
        return [
            'name'        => 'NewsletterForm',
            'description' => 'Newsletter Form component'
        ];
    }

    public function prepareVars()
    {
        $this->page['checkboxes'] = Checkbox::all();
    }

    public function onRun()
    {
        $this->prepareVars();
    }


    public function onSubscription()
    {
        $result;
        Db::transaction(function () use (&$result) {
            $data = post();

            $rules = [
                'email'    => 'required|email|between:6,255|unique:initbiz_newsletter_subscribers'
            ];

            $validation = Validator::make($data, $rules);

            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            // check if all required checkboxes are checked
            $requiredCheckboxes = Checkbox::required()->get();
            $checkedCheckboxes = $this->getCheckedCheckboxesId($data);

            //If currently checked checkboxes does not contain any of required checkboxes than throw
            foreach ($requiredCheckboxes as $requiredCheckbox) {
                if (!in_array($requiredCheckbox->id, $checkedCheckboxes)) {
                    throw new ValidationException(['requiredCheckboxes' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.checkbox_validation_failed')]);
                }
            }

            // try {
            $this->createSubscriberWithCheckboxes($data['email'], $checkedCheckboxes);
            $this->sendActivationEmail();
            $result = [
                'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_success')
            ];
            // } catch (Exception $e) {
            //     throw new SubscriptionException(Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_error'));
            // }
        });
        return $result;
    }

    /**
     * Get IDs of checked checkboxes from DB
     * @param  array $data array of sent data
     * @return array       array of checked checkboxes IDs
     */
    protected function getCheckedCheckboxesId($data)
    {
        $checked = [];
        $checkboxes = Checkbox::all();
        foreach ($checkboxes as $checkbox) {
            //If value in data is set than it means the checkbox is checked
            if (isset($data[$checkbox->slug][1])) {
                $checked[] = $checkbox->id;
            }
        }
        return $checked;
    }

    /**
     * Create subscriber with relations to checkboxes
     * @param  string $email   subscriber's email
     * @param  array  $checked array of checked checkboxes by the subscriber
     * @return void
     */
    protected function createSubscriberWithCheckboxes($email, $checked)
    {
        $this->subscriber = new Subscriber();
        $this->subscriber->email = $email;
        $this->subscriber->confirmed = false;
        $this->subscriber->token = Helpers::generateToken();
        $this->subscriber->save();
        $this->subscriber->checkboxes()->sync($checked);
    }

    /**
     * Send activation email to $this->subscriber
     * @return void
     */
    protected function sendActivationEmail()
    {
        $options = [
            'recipient_email' => $this->subscriber->email,
            'subject' => Lang::get('initbiz.newsletter::lang.mail.activation_subject'),
            'template' => 'initbiz.newsletter::mail.subscription',
            'activationLink' => Helpers::getNewsletterManagementUrl($this->subscriber->email, $this->subscriber->token)
        ];

        SendEmail::send($options);
    }
}
