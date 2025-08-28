<?php

namespace API\Services;

use API\Exception\AuthError;
use API\Repositories\AccountRepository;
use API\Services\Auth\SignUp;

class AuthService
{
    /**
     * @throws AuthError
     */
    public function signUp(string $email, string $username, string $password, string $confirm_password): bool
    {
        return (new SignUp())->signUp($email, $username, $password, $confirm_password);
    }

    /**
     * @throws AuthError
     */
    public function signIn($email, $password): string
    {
        $accountRepository = new AccountRepository();
        if (!$accountRepository->checkPassword($email, $password))
            throw new AuthError(19);
        return $accountRepository->createSession($accountRepository->getUserIDByEmail($email));
    }
}