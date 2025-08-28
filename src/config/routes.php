<?php

global $router;

use API\Objects\Account;

$router->createObject('Account', Account::class)
    ->POST('signUp',
        [
            "email" => ['required' => true],
            "username" => ['required' => true],
            "password" => ['required' => true],
            "confirm_password" => ['required' => true]
        ]
    )
    ->POST('signIn',
        [
            "email" => ['required' => true],
            "password" => ['required' => true],
        ]
    )
    ->GET('getProfileInfo',
        [
            "username" => ['required' => true],
        ]
    );