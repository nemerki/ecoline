<?php

namespace Initbiz\Newsletter\Components;

use Db;
use Lang;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Checkbox;
use October\Rain\Exception\ApplicationException;
use Initbiz\Newsletter\Classes\SubscriptionException;
use Initbiz\Newsletter\Models\Subscriber as Subscriber;
use Initbiz\Newsletter\Classes\UpdateSubscriberException;

class NewsletterConfirm extends ComponentBase
{
    protected $subscriber;
    protected $email;
    protected $token;
    public function componentDetails()
    {
        return [
            'name'        => 'NewsletterConfirm',
            'description' => 'NewsletterConfirm'
        ];
    }

    public function defineProperties()
    {
        return [
            'token' => [
                'title'       => 'initbiz.newsletter::lang.token.title',
                'description' => 'initbiz.newsletter::lang.token.description',
                'default'     => '{{ :token }}',
                'type'        => 'string'
            ],
            'email' => [
                'title'       => 'initbiz.newsletter::lang.email.title',
                'description' => 'initbiz.newsletter::lang.email.description',
                'default'     => '{{ :email }}',
                'type'        => 'string'
            ]
        ];
    }

    protected function prepareVars()
    {
        $this->page['confirmed'] = true;
        $this->token = $this->page['token'] = $this->property('token');
        $this->email = $this->page['email'] = $this->property('email');

        $this->subscriber = Subscriber::where('email', $this->email)
            ->where('token', $this->token)
            ->firstOrFail();
    }

    public function onRun()
    {
        try {
            $this->prepareVars();
            $userCheckboxes = $this->getSubscriberCheckboxesSlugs($this->subscriber);
            $notRequiredCheckboxes = $this->getAllNotRequiredCheckboxes()->toArray();
            $checkedNotRequiredCheckboxes = $this->addToCheckboxesIfChecked($notRequiredCheckboxes, $userCheckboxes);
            $this->page['checkboxes'] = $checkedNotRequiredCheckboxes;
            $this->activateSubscriber();
        } catch (\Exception $e) {
            throw new ApplicationException(Lang::get('initbiz.newsletter::lang.ajaxFormResponse.error'));
        }
    }

    public function onUnsubscribe()
    {
        try {
            $data = post();
            $this->deleteSubscriber($data);
            $result = [
                'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.unsubscribe_success'),
                'redirectUrl' => url('/')
            ];
        } catch (\Exception $e) {
            throw new SubscriptionException(Lang::get('initbiz.newsletter::lang.ajaxFormResponse.unsubscribe_failed'));
        }
        return $result;
    }

    protected function deleteSubscriber($data)
    {
        $subscriber = Subscriber::where('token', $data['token'])
            ->where('email', $data['email'])->first();
        $subscriber->checkboxes()->detach();
        $subscriber->delete();
    }

    protected function activate(Subscriber $subscriber)
    {
        $subscriber->update(['confirmed' => true]);
        $this->page['confirmedBox'] = "initbiz.newsletter::lang.confirmedBox.message";
    }

    protected function checkIfExist(Subscriber $subscriber)
    {
        return (count($subscriber->get())) ? true : false;
    }

    protected function getSubscriberCheckboxesSlugs($subscriber)
    {
        $checkboxes = $subscriber->checkboxes()
            ->notRequired()
            ->get()
            ->pluck('slug')
            ->toArray();

        if ($checkboxes == null) {
            $result =  [];
        } else {
            $result = $checkboxes;
        }
        return $result;
    }

    protected function addToCheckboxesIfChecked($notRequiredCheckboxes, $userCheckboxes)
    {
        foreach ($notRequiredCheckboxes as &$checkbox) {
            if (in_array($checkbox['slug'], $userCheckboxes)) {
                $checkedArray = ['checked' => true];
                $checkbox += $checkedArray;
            }
        }
        return $notRequiredCheckboxes;
    }

    protected function getAllNotRequiredCheckboxes()
    {
        return Checkbox::notRequired()->get();
    }

    protected function activateSubscriber()
    {
        if (!empty($this->subscriber)) {
            if ($this->page['subscriberExist'] = $this->checkIfExist($this->subscriber)) {
                if (!$this->subscriber->confirmed) {
                    $this->activate($this->subscriber);
                    $this->page['confirmed'] = false; //to display "Thank you for registering" message only once
                }
            }
        }
    }
    public function onUpdate()
    {
        $result = [];
        Db::transaction(function () use (&$result) {
            try {
                $data = post();
                $this->updateSubscriberCheckboxes($data);
                $result = ['content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.update_success')];
            } catch (\Exception $e) {
                throw new UpdateSubscriberException(Lang::get('initbiz.newsletter::lang.ajaxFormResponse.update_failed'));
            }
        });

        return $result;
    }

    protected function updateSubscriberCheckboxes($data)
    {
        $checkboxes = $this->getAllNotRequiredCheckboxes();
        $subscriber = $this->getSubscriber($data['token'], $data['email']);
        $subscriber->checkboxes()->detach();

        foreach ($checkboxes as $checkbox) {
            $checkbox = Checkbox::where('slug', $checkbox->slug)->firstOrFail();
            if (post($checkbox->slug) != null) {
                $subscriber->checkboxes()->save($checkbox);
            }
        }
    }

    public function getSubscriber($token, $email)
    {
        return Subscriber::where('token', $token)
            ->where('email', $email)
            ->firstOrFail();
    }
}
