<?php

namespace API\Services;

use API\Exception\AuthError;
use API\Exception\FileError;
use API\Exception\InvalidRoute;
use API\Modules\Makima;
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
                "avatar" => $user->avatar,
                "private_profile" => (boolean)$user->private_profile,
                "private_blog" => (boolean)$user->private_blog,
                ...!$user->private_profile ? [
                    "username_color" => $user->username_color,
                    "background" => $user->background,
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
        $resultUpdate = $this->setUserInfo($id, $info);

        switch ($resultUpdate)
        {
            case -1:
                $status = false;
                $message = 'No fields provided to update.';
                $code = 400;
                break;
            case 1:
                $status = true;
                $message = 'Information updated.';
                $code = 202;
                break;
            case 0:
            default:
                $status = false;
                $message = 'Error updating information';
                $code = 404;
                break;
        }

        return [['status' => $status, 'message' => $message], $code];
    }

    /**
     * @throws AuthError
     * @throws InvalidRoute
     * @throws FileError
     */
    public function setProfileAvatar(array $params): array
    {
        if (!$this->checkToken($params['token']))
            throw new AuthError(20);

        $id = $this->getUserIDBySessionToken($params['token']);
        $username = $this->getUsernameByID($id);

        $makima = new Makima($params['avatar']);
        $makima->setNewName("$username-avatar-%", true);
//        $makima->convert(Makima::PNG); <----- implemented!
        $makima->setUploadDirectory(UPLOAD_DIRECTORY . '/avatars');
        if (!$makima->upload())
            throw new FileError(9);
        $finalPath = $makima->getFinalPath();
        $result = $this->setUserCustomization($id, ['avatar' => $finalPath]);


        $JSON = [
            'status' => $result,
            'message' => ($result ? 'Successfully' : 'error') . ' updating avatar.'];
        $code = $result ? 200 : 400;

        return [$JSON, $code];
    }
}