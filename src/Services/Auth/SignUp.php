<?php

namespace API\Services\Auth;

use API\Exception\AuthError;
use API\Repositories\AccountRepository;

class SignUp
{
    private string $email;
    private string $username;
    private string $password;
    private string $confirm_password;

    private AccountRepository $accountRepository;

    /**
     * @throws AuthError
     */
    public function signUp(string $email, string $username, string $password, string $confirm_password): bool
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
        $this->accountRepository = new AccountRepository();

        return $this->register();
    }

    /**
     * @throws AuthError
     */
    private function register(): bool
    {
        if (!$this->minCharUsername()) throw new AuthError(10);
        if (!$this->maxCharUsername()) throw new AuthError(11);
        if (!$this->invalidUsername()) throw new AuthError(12);
        if (!$this->minCharPassword()) throw new AuthError(13);
        if (!$this->passwordIsEqual()) throw new AuthError(14);
        if (!$this->emailIsValidate()) throw new AuthError(15);
        if ($this->alreadyHaveUserN()) throw new AuthError(16);
        if ($this->alreadyHaveEmail()) throw new AuthError(17);

        return $this->accountRepository->createUser($this->email, $this->username, $this->password);
    }

    private function minCharUsername(): bool
    {
        return strlen($this->username) >= MIN_CHAR_USERNAME;
    }

    private function maxCharUsername(): bool
    {
        return strlen($this->username) <= MAX_CHAR_USERNAME;
    }

    private function invalidUsername(): bool
    {
        return preg_match("/^[A-Za-z0-9_]+$/", $this->username);
    }

    private function minCharPassword(): bool
    {
        return strlen($this->password) >= MIN_CHAR_PASSWORD;
    }

    private function passwordIsEqual(): bool
    {
        return $this->password === $this->confirm_password;
    }

    private function emailIsValidate(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL);
    }

    private function alreadyHaveUserN(): bool
    {
        return $this->accountRepository->checkUsername($this->username);
    }

    private function alreadyHaveEmail(): bool
    {
        return $this->accountRepository->checkEmail($this->email);
    }
}