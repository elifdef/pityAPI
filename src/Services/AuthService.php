<?php

namespace API\Services;

use API\Exception\AuthError;
use API\Repositories\AccountRepository;
use API\Services\Auth\SignUp;

class AuthService extends AccountRepository
{
    /**
     * @throws AuthError
     */
    public function signUp(string $email, string $username, string $password, string $confirm_password): array
    {
        $signUp = new SignUp();
        $result = $signUp->signUp($email, $username, $password, $confirm_password);
        $message = $result ? "Registration successfully." : "Error registration.";
        return [json_encode(
            [
                'status' => $result,
                'message' => $message
            ]
        ), 201];
    }

    /**
     * @throws AuthError
     */
    public function signIn($email, $password): array
    {
        if (!$this->checkPassword($email, $password))
            throw new AuthError(19);
        return [json_encode(
            [
                'status' => true,
                'token' => $this->createSession($this->getUserID($email))
            ]
        ), 200];
    }
}