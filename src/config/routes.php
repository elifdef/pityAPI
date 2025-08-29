<?php

global $router;

use API\Objects\Account;

$router->createObject('Account', Account::class)
    ->POST('signUp',
        [
            'email' => ['type' => 'string', 'required' => true],
            'username' => ['type' => 'string', 'required' => true],
            'password' => ['type' => 'string', 'required' => true],
            'confirm_password' => ['type' => 'string', 'required' => true]
        ]
    )
    ->POST('signIn',
        [
            'email' => ['type' => 'string', 'required' => true],
            'password' => ['type' => 'string', 'required' => true],
        ]
    )
    ->GET('getProfileInfo',
        [
            'username' => ['type' => 'string', 'required' => true],
        ]
    )
    ->PATCH('setProfileInfo',
        [
            'token' => ['type' => 'string', 'required' => true],
            'first_name' => ['type' => 'string', 'required' => false],
            'last_name' => ['type' => 'string', 'required' => false],
            'gender_id' => ['type' => 'integer', 'required' => false],
            'country_id' => ['type' => 'integer', 'required' => false],
            'birthdate' => ['type' => 'string', 'required' => false]
        ]
    )
    ->POST('setProfileAvatar',
        [
            'token' => ['type' => 'string', 'required' => true],
            'avatar' => ['type' => 'array', 'required' => true, 'isFile' => true]
        ]
    );