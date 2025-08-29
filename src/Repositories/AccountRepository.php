<?php

namespace API\Repositories;

use API\DB\DB;
use API\Exception\AuthError;

class AccountRepository extends DB
{
    protected function checkUsername(string $username): bool
    {
        $check = $this->con->prepare("
        SELECT `id` 
        FROM `users` 
        WHERE `username` = :username");

        $username = htmlspecialchars($username);
        $check->bindParam(':username', $username);
        $check->execute();

        return $check->rowCount() > 0;
    }

    protected function checkEmail(string $email): bool
    {
        $check = $this->con->prepare("
        SELECT `id` 
        FROM `users` 
        WHERE `email` = :email");

        $email = htmlspecialchars($email);
        $check->bindParam(':email', $email);
        $check->execute();

        return $check->rowCount() > 0;
    }

    protected function createUser(string $email, string $username, string $password): bool
    {
        $createUser = $this->con->prepare("
        INSERT INTO `users`
        (`email`,`username`, `password`, `token`,`online_status`, `IP`, `role_id`) VALUES 
        (:email, :username, :password, :token, false, :ip, 0)");

        $email = htmlspecialchars($email);
        $username = htmlspecialchars($username);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(LENGTH_USER_TOKEN));
        $ip = $GLOBALS['request']->getClientIP();

        $createUser->bindParam(':email', $email);
        $createUser->bindParam(':username', $username);
        $createUser->bindParam(':password', $password);
        $createUser->bindParam(':token', $token);
        $createUser->bindParam(':ip', $ip);

        $createUser->execute();

        return $createUser->rowCount() > 0;
    }

    protected function createPrivacy(int $id): bool
    {
        $createPrivacy = $this->con->prepare("
        INSERT INTO `privacy` 
        (`user_id`, `hide_birthdate`, `hide_online`, `hide_country`, `hide_gender`, `hide_name`) VALUES (?, false, false, false, false, false)");

        $createPrivacy->execute([$id]);

        return $createPrivacy->rowCount() > 0;
    }

    protected function createCustomization(int $id): bool
    {
        $createPrivacy = $this->con->prepare("
        INSERT INTO `customization` 
        (`user_id`, `avatar`, `background`) VALUES (?, null,  null)");

        $createPrivacy->execute([$id]);

        return $createPrivacy->rowCount() > 0;
    }

    /**
     * @throws AuthError
     */
    public function checkPassword(string $email, string $password): bool
    {
        $valid_password = $this->con->prepare("
        SELECT `password` 
        FROM `users` 
        WHERE `email` = :email");

        $email = htmlspecialchars($email);
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

        $createSession = $this->con->prepare("
        INSERT INTO `sessions` 
        (`user_id`, `token`, `created_at`, `client_ip`, `client_os`, `client_browser`) VALUES 
        (?, :token, NOW(), ?, ?, ?)");

        $createSession->bindParam(':token', $sessionToken);
        $createSession->execute([$userID, $IP, $OS, $browser]);

        return $sessionToken;
    }

    protected function getSessionTokenByID(int $userID): ?string
    {
        // також перевір з якого пристрою заходить користувач
        // якщо з пк - свій токен, з телефона/холодильника/тостера - свій
        // 19.08.2025   23:34
        global $request;

        $getToken = $this->con->prepare("
        SELECT `token` 
        FROM `sessions` 
        WHERE `user_id` = ? 
          AND `client_ip` = ? 
          AND `client_os` = ? 
          AND `client_browser` = ?");

        $getToken->execute([$userID, $request->getClientIP(), $request->getOS(), $request->getBrowser()]);
        $fetch = $getToken->fetchObject();

        return empty($fetch->token) ? null : $fetch->token;
    }

    public function getUserIDByEmail(string $email): ?int
    {
        $getID = $this->con->prepare("
        SELECT `id` 
        FROM `users` 
        WHERE `email` = :email");

        $email = htmlspecialchars($email);
        $getID->bindParam(':email', $email);
        $getID->execute();
        $fetch = $getID->fetchObject();

        return empty($fetch->id) ? null : $fetch->id;
    }

    protected function getUserIDByUsername(string $username): ?int
    {
        $getID = $this->con->prepare("
        SELECT `id` 
        FROM `users` 
        WHERE `username` = :username");

        $username = htmlspecialchars($username);
        $getID->bindParam(':username', $username);
        $getID->execute();
        $fetch = $getID->fetchObject();

        return empty($fetch->id) ? null : $fetch->id;
    }

    protected function getUserIDBySessionToken(string $token): ?string
    {
        $getID = $this->con->prepare("
        SELECT `user_id` 
        FROM `sessions` 
        WHERE `token` = :token");

        $getID->bindParam(':token', $token);
        $getID->execute();

        return $getID->fetchObject()->user_id;
    }

    protected function checkIP(?string $ip = null): bool
    {
        $givenIP = $ip ?? $GLOBALS['request']->getClientIP();
        $check = $this->con->prepare("
        SELECT `id` 
        FROM `users` 
        WHERE `ip` = ?");

        $check->execute([$givenIP]);

        return $check->rowCount() > 0;
    }

    protected function getUsernameByID(int $id): ?string
    {
        $username = $this->con->prepare("
        SELECT `username` 
        FROM `users` 
        WHERE `id` = ?");

        $username->execute([$id]);

        return $username->fetchObject()->username;
    }

    protected function checkToken(string $token): bool
    {
        $check = $this->con->prepare("
        SELECT * 
        FROM `sessions` 
        WHERE `token` = :token");

        $check->bindParam(':token', $token);
        $check->execute();

        return $check->rowCount() > 0;
    }

    protected function getUserInfoByID(int $id): object
    {
        $getUser = $this->con->prepare("
        SELECT users.id, `username`,`email`,`first_name`,`last_name`,`gender_id`,`gender_name` AS `gender`,`private_blog`,`private_profile`,`country_id`, CONCAT(countries.name, ' ', countries.emoji) AS `country_name`,`birthdate`,`created_at`,`online_status`,`role_id`, roles.name AS `role`, customization.avatar AS `avatar`, customization.username_color AS `username_color`, customization.background AS `background` 
        FROM `users` 
            INNER JOIN genders ON genders.id = users.gender_id 
            INNER JOIN countries ON countries.id = users.country_id 
            INNER JOIN roles ON roles.id = users.role_id 
            INNER JOIN customization ON customization.user_id = users.id
        WHERE users.id = ?");

        $getUser->execute([$id]);

        return $getUser->fetchObject();
    }

    protected function getUserPrivacy(int $id): object
    {
        $getPrivacy = $this->con->prepare("
        SELECT * 
        FROM `privacy` 
        WHERE `user_id` = ?");

        $getPrivacy->execute([$id]);

        return $getPrivacy->fetchObject();
    }

    protected function setUserInfo(int $id, array $info): int
    {
        $allowed = ['first_name', 'last_name', 'gender_id', 'country_id', 'birthdate'];
        return $this->setInfo('users', $allowed, 'id', $id, $info);
    }

    protected function setUserCustomization(int $id, array $info): int
    {
        $allowed = ['avatar', 'username_color', 'background'];
        return $this->setInfo('customization', $allowed, 'user_id', $id, $info);
    }

    private function setInfo(string $table, array $allowedColumns, string $idField, int $id, array $data): bool|int
    {

        $update = array_filter($data, function ($key) use ($allowedColumns)
        {
            return in_array($key, $allowedColumns, true);
        }, ARRAY_FILTER_USE_KEY);

        if (empty($update))
        {
            return -1;
        }

        $set = implode(", ", array_map(fn($k) => "$k = :$k", array_keys($update)));
        $sql = "
        UPDATE `$table` 
        SET $set 
        WHERE `$idField` = :id";

        $stmt = $this->con->prepare($sql);
        $update['id'] = $id;

        return $stmt->execute($update);
    }
}