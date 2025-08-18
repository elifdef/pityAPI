<?php

namespace API\DB;

use PDO;
use PDOException;

class DB
{
    protected PDO $con;
    private string $host_name;
    private string $table_name;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->host_name = $_ENV['DATABASE_HOSTNAME'];
        $this->table_name = $_ENV['DATABASE_TABLE'];
        $this->username = $_ENV['DATABASE_USERNAME'];
        $this->password = $_ENV['DATABASE_PASSWORD'];
        try {
            $dsn = "mysql:host=" . $this->host_name . ";dbname=" . $this->table_name;
            $this->con = new PDO($dsn, $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            error_log($error->getMessage());
        }
    }
}