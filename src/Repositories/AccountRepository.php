<?php

namespace API\Repositories;

use API\DB\DB;
use API\Exception\AuthError;

class AccountRepository extends DB
{
    protected function checkUsername(string $username): bool
    {
        $check = $this->con->prepare("SELECT `id` FROM `users` WHERE `username` = ?");
        $check->execute([htmlspecialchars($username)]);
        return $check->rowCount() > 0;
    }

    protected function checkEmail(string $email): bool
    {
        $check = $this->con->prepare("SELECT `id` FROM `users` WHERE `email` = ?");
        $check->execute([$email]);
        return $check->rowCount() > 0;
    }

    protected function createUser(string $email, string $username, string $password): bool
    {
        global $request;
        $createUser = $this->con->prepare("INSERT INTO `users`
        (`email`,`username`, `password`, `token`,`online_status`, `IP`, `role_id`) VALUES (?, ?, ?, ?, false, ?, 0)");
        $token = bin2hex(random_bytes(LENGTH_USER_TOKEN));
        $ip = $request->getClientIP();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $createUser->execute([$email, $username, $password, $token, $ip]);
        return $createUser->rowCount() > 0;
    }

    protected function createPrivacy(int $id): bool
    {
        $createPrivacy = $this->con->prepare("INSERT INTO `privacy` (`user_id`, `hide_birthdate`, `hide_online`, `hide_country`, `hide_gender`, `hide_name`) VALUES (?, false, false, false, false, false)");
        $createPrivacy->execute([$id]);
        return $createPrivacy->rowCount() > 0;
    }

    /**
     * @throws AuthError
     */
    public function checkPassword(string $email, string $password): bool
    {
        $valid_password = $this->con->prepare("SELECT `password` FROM `users` WHERE `email` = :email");
        $valid_password->bindParam(':email', $email);
        $valid_password->execute();
        $passwordDB = $valid_password->fetchObject();
        $passwordDB = $passwordDB->password ?? throw new AuthError(19);
        return password_verify($password, $passwordDB);
    }

    public function createSession(int $userID): string
    {
        global $request;
        $sessionToken = bin2hex(random_bytes(LENGTH_USER_SESSION_TOKEN));
        $OS = $request->getOS();
        $browser = $request->getBrowser();
        $IP = $request->getClientIP();

        $alreadyLogin = $this->getSessionTokenByID($userID);
        if (!empty($alreadyLogin))
            return $alreadyLogin;

        $createSession = $this->con->prepare("INSERT INTO `sessions` (`user_id`, `token`, `created_at`, `client_ip`, `client_os`, `client_browser`)VALUES (?, ?, NOW(), ?, ?, ?)");
        $createSession->execute([$userID, $sessionToken, $IP, $OS, $browser]);
        return $sessionToken;
    }

    protected function getSessionTokenByID(int $userID): ?string
    {
        // також перевір з якого пристрою заходить користувач
        // якщо з пк - свій токен, з телефона/холодильника/тостера - свій
        // 19.08.2025   23:34
        global $request;

        $getToken = $this->con->prepare("SELECT `token` FROM `sessions` WHERE `user_id` = ? AND `client_ip` = ? AND `client_os` = ? AND `client_browser` = ?");

        $getToken->execute([$userID, $request->getClientIP(), $request->getOS(), $request->getBrowser()]);
        $fetch = $getToken->fetchObject();
        return empty($fetch->token) ? null : $fetch->token;
    }

    public function getUserIDByEmail(string $email): ?int
    {
        $getID = $this->con->prepare("SELECT `id` FROM `users` WHERE `email` = ?");
        $getID->execute([$email]);
        $fetch = $getID->fetchObject();
        return empty($fetch->id) ? null : $fetch->id;
    }

    protected function getUserIDByUsername(string $username): ?int
    {
        $getID = $this->con->prepare("SELECT `id` FROM `users` WHERE `username` = ?");
        $getID->execute([$username]);
        $fetch = $getID->fetchObject();
        return empty($fetch->id) ? null : $fetch->id;
    }

    protected function getUserIDBySessionToken(string $token): ?string
    {
        $getID = $this->con->prepare("SELECT `user_id` FROM `sessions` WHERE `token` = ?");
        $getID->execute([htmlspecialchars($token)]);
        return $getID->fetchObject()->user_id;
    }

    protected function checkIP(?string $ip = null): bool
    {
        global $request;
        $givenIP = $ip ?? $request->getClientIP();
        $check = $this->con->prepare("SELECT `id` FROM `users` WHERE `ip` = ?");
        $check->execute([$givenIP]);
        return $check->rowCount() > 0;
    }

    protected function checkToken(string $token): bool
    {
        $check = $this->con->prepare("SELECT * FROM `sessions` WHERE `token` = ?");
        $check->execute([$token]);
        return $check->rowCount() > 0;
    }

    protected function getUserInfoByID(int $id): object
    {
        $getUser = $this->con->prepare("SELECT users.id, `username`,`email`,`first_name`,`last_name`,`gender_id`,`gender_name` AS `gender`,`private_blog`,`private_profile`,`country_id`, CONCAT(countries.name, ' ', countries.emoji) AS `country_name`,`birthdate`,`created_at`,`online_status`,`role_id`, roles.name AS `role` FROM `users` INNER JOIN genders ON genders.id = users.gender_id INNER JOIN countries ON countries.id = users.country_id INNER JOIN roles ON roles.id = users.role_id WHERE users.id = ?");
        $getUser->execute([$id]);
        return $getUser->fetchObject();
    }

    protected function getUserPrivacy(int $id): object
    {
        $getPrivacy = $this->con->prepare("SELECT * FROM `privacy` WHERE `user_id` = ?");
        $getPrivacy->execute([$id]);
        return $getPrivacy->fetchObject();
    }

    protected function setUserInfoByID(int $id, array $info): int
    {
        $allowed = ['first_name', 'last_name', 'gender_id', 'country_id', 'birthdate'];
        $update = [];

        foreach ($info as $key => $value)
        {
            if (in_array($key, $allowed, true))
            {
                $update[$key] = $value;
            }
        }

        if (empty($update))
        {
            return -1;
        }

        $set = implode(", ", array_map(fn($k) => "$k = :$k", array_keys($update)));
        $sql = "UPDATE users SET $set WHERE id = :id";

        $stmt = $this->con->prepare($sql);
        $update['id'] = $id;

        return $stmt->execute($update);
    }
}