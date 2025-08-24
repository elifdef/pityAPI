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
        $json = $request->getPOSTObject();

        $email = $json->email;
        $username = $json->username;
        $password = $json->password;
        $confirm_password = $json->confirm_password;

        return (new AuthService)->signUp($email, $username, $password, $confirm_password);
    }


    /**
     * @throws AuthError
     * @throws InvalidRoute
     */
    public static function signIn(): array
    {
        global $request;
        $json = $request->getPOSTObject();

        $email = $json->email;
        $password = $json->password;

        return (new AuthService)->signIn($email, $password);
    }

    /**
     * @throws AuthError
     */
    public static function getProfile(): array
    {

        global $request;
        $username = $request->getGET()['username'];

        $user = (new AuthService)->getUserInfo($username);
        return [json_encode($user), 200];
    }
}