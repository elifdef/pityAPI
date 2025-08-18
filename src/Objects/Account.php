<?php

namespace API\Objects;

use API\Exception\AuthError;
use API\Exception\InvalidRoute;
use API\Services\AuthService;

class Account
{

    /**
     * @throws InvalidRoute
     * @throws AuthError
     */

    public static function signUp(): array
    {
        global $request;
        $json = $request->getPostObject();

        $email = $json->email;
        $username = $json->username;
        $password = $json->password;
        $confirm_password = $json->confirm_password;

        return AuthService::signUp($email, $username, $password, $confirm_password);
    }
}