<?php

global $router;

use API\Objects\Account;

$router->createObject('Account', Account::class)
    ->POST('signUp', ["email", "username", "password", "confirm_password"])
    ->POST('signIn', ["email", "password"])
    ->GET('getProfileInfo', ['username']);