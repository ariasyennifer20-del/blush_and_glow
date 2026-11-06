<?php
// config/Database.php

class Database {
    private static $host = 'localhost';
    private static $dbName = 'blush_and_glow';
    private static $username = 'root';
    private static $password = '';
    private static $conn = null;

    // Método para obtener la conexión a la base de datos
    public static function getConnection() {
        if (self::$conn === null) {
            try {
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbName . ';charset=utf8mb4';
                
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$conn = new PDO($dsn, self::$username, self::$password, $options);
            } catch (PDOException $e) {
                error_log($e->getMessage());
                die("No se pudo conectar con la base de datos. Inténtalo más tarde.");
            }
        }

        return self::$conn;
    }
}