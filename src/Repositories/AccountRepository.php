<?php

namespace API\Repositories;

use API\DB\DB;

class AccountRepository extends DB
{
    public function checkUsername(string $username): bool
    {
        $check = $this->con->prepare("SELECT `id` FROM `users` WHERE `username` = ?");
        $check->execute([htmlspecialchars($username)]);
        return $check->rowCount() > 0;
    }

    public function checkEmail(string $email): bool
    {
        $check = $this->con->prepare("SELECT `id` FROM `users` WHERE `email` = ?");
        $check->execute([$email]);
        return $check->rowCount() > 0;
    }

    public function createUser(string $email, string $username, string $password): bool
    {
        $createUser = $this->con->prepare("INSERT INTO `users`
        (`email`,`username`, `password`, `token`, `online_status`, `role`) VALUES (?,?,?,?,false,?)");
        $token = bin2hex(random_bytes(LENGTH_USER_TOKEN));
        $createUser->execute([$email, $username, $password, $token, DEFAULT_ROLE]);
        return $createUser->rowCount() > 0;
    }
}