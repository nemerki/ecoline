<?php

namespace Initbiz\Newsletter\Classes;

use Mail;
use Queue;
use Config;

class SendEmail
{
    /**
     * Send email using this method. It will queue or directly send the email
     * @param array $options containing recipient_email, recipient_name, subject and content
     * @return void
     */
    public static function send($options)
    {
        $useQueue = Config::get('initbiz.newsletter::useQueue', false);

        if ($useQueue) {
            Queue::push(Self::class, $options);
        } else {
            Self::sendInternal($options);
        }
    }

    /*
     * Function to be used in queues
     */
    public function fire($job, $options)
    {
        Self::sendInternal($options);
    }

    /**
     * Method that actually sends e-mail
     *
     * @param array $options containing recipient_email, recipient_name, subject and content
     * @return void
     */
    public static function sendInternal($options)
    {
        $recipient_name = $options['recipient_name'] ?? $options['recipient_email'];
        $recipient_email = $options['recipient_email'];
        $subject = $options['subject'];

        Mail::send($options['template'], $options, function ($message) use ($recipient_email, $recipient_name, $subject) {
            $message->to($recipient_email, $recipient_name);
            $message->subject($subject);
        });
    }

}
