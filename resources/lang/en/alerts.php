<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain alert messages for various scenarios
    | during CRUD operations. You are free to modify these language lines
    | according to your application's requirements.
    |
    */

    'backend' => [
        'product' => [
            'created' => 'The product was successfully created.',
            'deleted' => 'The product was successfully deleted.',
            'updated' => 'The product was successfully updated.',
            'nocategory' => 'The Store has no category.'
        ],
        'store' => [
            'created' => 'The store was successfully created.',
            'deleted' => 'The store was successfully deleted.',
            'updated' => 'The store was successfully updated.'
        ],
        'brand' => [
            'created' => 'The brand was successfully created.',
            'deleted' => 'The brand was successfully deleted.',
            'updated' => 'The brand was successfully updated.'
        ],
        'category' => [
            'created' => 'The category was successfully created.',
            'deleted' => 'The category was successfully deleted.',
            'updated' => 'The category was successfully updated.'
        ],
        'roles' => [
            'created' => 'The role was successfully created.',
            'deleted' => 'The role was successfully deleted.',
            'updated' => 'The role was successfully updated.',
        ],

        'users' => [
            'cant_resend_confirmation' => 'The application is currently set to manually approve users.',
            'confirmation_email'  => 'A new confirmation e-mail has been sent to the address on file.',
            'confirmed'              => 'The user was successfully confirmed.',
            'created'             => 'The user was successfully created.',
            'deleted'             => 'The user was successfully deleted.',
            'deleted_permanently' => 'The user was deleted permanently.',
            'restored'            => 'The user was successfully restored.',
            'session_cleared'      => "The user's session was successfully cleared.",
            'social_deleted' => 'Social Account Successfully Removed',
            'unconfirmed' => 'The user was successfully un-confirmed',
            'updated'             => 'The user was successfully updated.',
            'updated_password'    => "The user's password was successfully updated.",
        ],
    ],

    'frontend' => [
        'contact' => [
            'sent' => 'Your information was successfully sent. We will respond back to the e-mail provided as soon as we can.',
        ],
    ],
];
