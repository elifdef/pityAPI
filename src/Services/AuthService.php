<?php

namespace API\Services;

use API\Exception\AuthError;
use API\Services\Auth\SignUp;

class AuthService
{
    /**
     * @throws AuthError
     */
    public static function signUp(string $email, string $username, string $password, string $confirm_password): array
    {
        $signUp = new SignUp();
        $result = $signUp->signUp($email, $username, $password, $confirm_password);
        if (!$result) throw new AuthError(18);
        return [json_encode(
            [
                'status' => true,
                'message' => 'Registered successfully.'
            ]
        ), 201];
    }
}