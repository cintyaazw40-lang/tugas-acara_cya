<?php

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host = 'localhost';
            $db   = 'GANTI_usercpanel_dbworkshop';
            $user = 'GANTI_usercpanel_dbuser';
            $pass = 'GANTI_password_db';

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$connection;
    }
}
