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
                'token' => $this->createSession($this->getUserIDByEmail($email))
            ]
        ), 200];
    }

    /**
     * @throws AuthError
     */
    public function getUserInfo(string $username): array
    {
        if (!$this->checkUsername($username))
            throw new AuthError(21);

        $id = $this->getUserIDByUsername($username);
        $user = $this->getUserInfoByID($id);
        return
            ($user->private_profile)
                ? [
                "username" => $user->username,
                "private_profile" => $user->private_profile,
                "role" => $user->role
            ]
                : [
                "id" => $user->id,
                "username" => $user->username,
                "email" => $user->email,
                "first_name" => $user->first_name,
                "last_name" => $user->last_name,
                "gender" => $user->gender,
                "private_blog" => $user->private_blog,
                "private_profile" => $user->private_profile,
                "country_name" => $user->country_name,
                "birthdate" => $user->birthdate,
                "created_at" => $user->created_at,
                "online_status" => $user->online_status,
                "role" => $user->role
            ];
    }
}