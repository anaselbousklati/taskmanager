<?php

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host   = getenv('DB_HOST') ?: 'localhost';
            $port   = getenv('DB_PORT') ?: '3306';
            $dbname = getenv('DB_NAME') ?: 'taskmanager';
            $user   = getenv('DB_USER') ?: 'root';
            $pass   = getenv('DB_PASS') ?: '';

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
                PDO::ATTR_EMULATE_PREPARES   => false,                  
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // Toon geen technische details aan de gebruiker (security!)
                error_log('Database verbindingsfout: ' . $e->getMessage());
                die('Er is een probleem met de databaseverbinding. Controleer je .env instellingen.');
            }
        }

        return self::$instance;
    }
}
