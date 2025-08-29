<?php

namespace API\Objects;

use API\Exception\AuthError;
use API\Services\AccountService;
use API\Services\AuthService;

class Account
{
    /**
     * @throws AuthError
     */
    public static function signUp(array $params): array
    {
        $email = $params['email'];
        $username = $params['username'];
        $password = $params['password'];
        $confirm_password = $params['confirm_password'];

        $result = (new AuthService)->signUp($email, $username, $password, $confirm_password);
        $message = $result ? "Registration successfully." : "Error registration.";
        return [json_encode(['status' => $result, 'message' => $message]), $result ? 201 : 406];
    }

    /**
     * @throws AuthError
     */
    public static function signIn(array $params): array
    {
        $email = $params['email'];
        $password = $params['password'];

        $token = (new AuthService)->signIn($email, $password);
        return [json_encode(['status' => true, 'token' => $token]), 200];
    }

    /**
     * @throws AuthError
     */
    public static function getProfileInfo(array $params): array
    {
        $user = (new AccountService)->getUserInfo($params['username']);
        return [json_encode($user), 200];
    }

    /**
     * @throws AuthError
     */
    public static function setProfileInfo(array $params): array
    {
        [$message, $code] = (new AccountService)->setProfileInfo($params);
        return [json_encode($message), $code];
    }

    public static function setProfileAvatar(array $params): array
    {
        [$message, $code] = (new AccountService)->setProfileAvatar($params);
        return [json_encode($message), $code];
    }
}