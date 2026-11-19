<?php
class Database {
    private static ?PDO $connection = null;

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $host    = 'localhost';
            $db      = 'db_workshop';
            $charset = 'utf8mb4';
            $dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];
            try {
                self::$connection = new PDO($dsn, 'root', '', $options);
            } catch (PDOException $e) {
                // Detail teknis hanya ditampilkan pada mode development.
                $errors = (defined('APP_ENV') && APP_ENV === 'development')
                    ? ['database' => [$e->getMessage()]]
                    : [];
                Response::json(500, false, 'Koneksi database gagal.', null, $errors);
            }
        }
        return self::$connection;
    }
}
