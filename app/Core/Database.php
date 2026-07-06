<?php

/**
 * Conexão única (singleton) com o banco de dados usando PDO.
 */
class Database
{
    private static ?PDO $instancia = null;

    public static function getConnection(): PDO
    {
        if (self::$instancia === null) {
            $config = require __DIR__ . '/../../config.php';

            $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";

            try {
                self::$instancia = new PDO($dsn, $config['db_user'], $config['db_pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
            }
        }

        return self::$instancia;
    }
}
