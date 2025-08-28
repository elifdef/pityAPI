<?php

namespace API\Services;

use API\Exception\AuthError;
use API\Repositories\AccountRepository;

class AccountService extends AccountRepository
{
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
                "private_profile" => (boolean)$user->private_profile,
                "private_blog" => (boolean)$user->private_blog,
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

    /**
     * @throws AuthError
     */
    public function setProfileInfo(array $info): array
    {
        if (!$this->checkToken($info['token']))
            throw new AuthError(20);

        $id = $this->getUserIDBySessionToken($info['token']);
        $resultUpdate = $this->setUserInfoByID($id, $info);

        switch ($resultUpdate)
        {
            case -1:
                $status = true;
                $message = 'No fields provided to update.';
                $code = 400;
                break;
            case 0:
                $status = false;
                $message = 'Error updating information';
                $code = 404;
                break;
            case 1:
                $status = true;
                $message = 'Information updated.';
                $code = 202;
                break;
        }

        return [['status' => $status, 'message' => $message], $code];
    }
}