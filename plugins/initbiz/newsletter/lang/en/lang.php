<?php

return [
    'plugin' => [
        'name' => 'Newsletter',
        'description' => 'Plugin for newsletter.',
        'author' => 'InIT.biz Ltd.'
    ],
    'mailTemplates' => [
        'message' => 'Message that is being sent to subscribers',
        'confirmation' => 'Mail with confirmation message'
    ],
    'menu' => [
        'newsletter' => 'Newsletter',
        'messages' => 'Messages',
        'subscribers' => 'Subscribers',
        'checkboxes' => 'Checkboxes',
    ],
    'title' => [
        'newsletter' => 'Newsletter',
        'messages' => 'message',
        'subscribers' => 'Subscribers',
        'checkboxes' => 'Checkboxes',
    ],
    'subscribers' => [
        'import_subscribers' => 'Import subscribers',
        'export_subscribers' => 'Export subscribers',
        'email' => 'Email',
    ],
    'permission' => [
        'messages' => 'Messages managing',
        'subscribers' => 'Subscribers managing',
    ],
    'new' => [
        'messages' => 'New message',
        'checkbox' => 'New checkbox',
        'subscriber' => 'New subscriber',
    ],
    'checkboxes' => [
        'export' => 'Export Checkboxes',
        'import' => 'Import Checkboxes',
        'name' => 'Name',
        'text' => 'Text appearing next to the checkbox',
        'required' => 'Required',
    ],
    'messages' => [
        'title' => 'Message title',
        'content' => 'Message content',
        'slug' => 'Slug',
        'sent' => 'Message was sent',
        'send' => 'Send message to subscribers',
        'send_to_all' => 'Send message to all subscribers',
        'send_to_agreed' => 'Send message only to those who agreed optional checkbox',
        'email_template' => 'Select a template of e-mail to use'
    ],
    'columns' => [
        'title' => 'Title',
        'slug' => 'Slug',
        'sent' => 'Sent',
        'created' => 'Created',
        'updated' => 'Updated',
        'name' => 'Name',
        'text' => 'Text',
        'required' => 'Required',
        'email' => 'E-mail',
        'token' => 'Token',
        'confirmed' => 'Confirmed',
        'checkboxes' => 'Checkboxes',
    ],
    'userColumns' => [
        'email' => 'E-mail',
        'agreement' => 'Agreement',
        'joined' => 'Joined',
        'confirmed' => 'Confirmed',
    ],
    'flash' => [
        'delete' => 'Are you sure you want to delete this checkbox?',
    ],
    'flash_checkboxes' => [
        'deleted' => 'Checkbox succesfully deleted',
        'saved' => 'Checkbox succesfully saved',
        'updated' => 'Checkbox succesfully updated',
    ],
    'flash_checkboxes' => [
        'deleted' => 'Subscriber succesfully deleted',
        'saved' => 'Subscriber succesfully saved',
        'updated' => 'Subscriber succesfully updated',
    ],
    'token' => [
        'title' => 'Subscriber unique code',
        'description' => 'Code that subscriber will get and can use to authorize'
    ],
    'email' => [
        'title' => 'Subscribers e-mail address',
        'description' => 'Subscribers e-mail address'
    ],
    'confirmedbox' => [
        'message' => 'Thank you for signing up to our newsletter'
    ],
    'form' => [
        'button_text' => 'Sign up',
        'placeholder_email' => 'Email',
        'label_email' => 'Email',
        'sign_up_thanks' => 'Thank you for subscribing our newsletter',
        'sign_up_error' => 'Oops, something went wrong.',
    ],
    'manage' => [
        'thank_you_message' => 'Thank you for subscribing our newsletter',
        'config_heading' => 'Customize your newsletter configuration',
        'sign_out_button_text' => 'Sign out from our newsletter',
        'update_button_text' => 'Update'
    ],
    'ajaxFormResponse' => [
        'sign_up_success' => 'Thank you for signing up to our newsletter! Please confirm your email',
        'sign_up_error' => 'Error. Something went wrong.',
        'email_validation_failed' => 'E-mail must be valid',
        'email_cannot_be_empty' => 'E-mail address field cannot be empty',
        'subscriber_save_success' => 'Subscriber successfully saved',
        'subscriber_save_failed' => 'Saving subscriber failed',
        'checkbox_validation_failed' => 'You must accept all required checkboxes',
        'unsubscribe_success' => 'Successfully unsubscribe.',
        'unsubscribe_failed' => 'Error. Something went wrong.',
        'wrong_path' => 'Wrong path.',
        'update_failed' => 'Error. Something went wrong.',
        'update_success' => 'Successfully updated.',
        'unsubscribe' => 'Unsubscribe',
        'error' => 'Error. Something went wrong.',
    ],
    'mail' => [
        'activation_subject' => 'Confirm your e-mail address'
    ]
];
