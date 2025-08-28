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
    public function signUp(string $email, string $username, string $password, string $confirm_password): bool
    {
        return (new SignUp())->signUp($email, $username, $password, $confirm_password);
    }

    /**
     * @throws AuthError
     */
    public function signIn($email, $password): string
    {
        if (!$this->checkPassword($email, $password))
            throw new AuthError(19);
        return $this->createSession($this->getUserIDByEmail($email));
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
        $privacy = $this->getUserPrivacy($id);
        return
            [
                "id" => $user->id,
                "username" => $user->username,
                "private_profile" => $user->private_profile,
                "private_blog" => $user->private_blog,
                ...!$user->private_profile ? [
                    "name" =>
                        [
                            "first" => $privacy->hide_name ? null : $user->first_name,
                            "last" => $privacy->hide_name ? null : $user->last_name,
                            "visibility" => !$privacy->hide_name
                        ],
                    "gender" => [
                        "value" => $privacy->hide_gender ? null : $user->gender,
                        "visibility" => !$privacy->hide_gender
                    ],
                    "country" =>
                        [
                            "value" => $privacy->hide_country ? null : $user->country_name,
                            "visibility" => !$privacy->hide_country
                        ],
                    "birthdate" =>
                        [
                            "value" => $privacy->hide_birthdate ? null : $user->birthdate,
                            "visibility" => !$privacy->hide_birthdate
                        ],
                    "date_registration" =>
                        [
                            "value" => $privacy->hide_regdate ? null : $user->created_at,
                            "visibility" => !$privacy->hide_regdate
                        ],
                    "online" =>
                        [
                            "value" => $privacy->hide_online ? null : (boolean)$user->online_status,
                            "visibility" => !$privacy->hide_online
                        ],
                    "role" =>
                        [
                            "value" => $privacy->hide_role ? null : $user->role,
                            "visibility" => !$privacy->hide_role
                        ]
                ] : []
            ];
    }
}