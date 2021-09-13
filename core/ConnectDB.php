<?php

namespace core;

use \PDO;
use PDOException;

class ConnectDB
{
    private static $connect = null;

    private function __construct()
    {
    }
    private function __clone()
    {
    }
    private function __destruct()
    {
        self::$connect = null;
    }
    public static function connect()
    {
        if (!self::$connect) {
            try {
                self::$connect =  new \PDO("mysql:host=" . SERVER_NAME . ";dbname=" . DB_NAME, USER_NAME, PASSWORD);
                self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
                require_once './views/_shared/error.php';
            }
        }
        return self::$connect;
    }
}
