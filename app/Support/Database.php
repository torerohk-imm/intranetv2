<?php
namespace App\Support;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function init(array $config): void
    {
        if (self::$connection) {
            return;
        }

        $dsn = sprintf(
            '%s:host=%s;port=%s;dbname=%s;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Error de conexión a la base de datos: ' . $e->getMessage());
        }
    }

    public static function connection(): PDO
    {
        if (!self::$connection) {
            throw new \RuntimeException('La conexión a la base de datos no está inicializada.');
        }

        return self::$connection;
    }
}
