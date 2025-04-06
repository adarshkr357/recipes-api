<?php

namespace App\Utils;

use PDO;
use PDOException;

class Database
{
    private static $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/config.php';
            $db = $config['db'];
            $dsn = "pgsql:host={$db['host']};port={$db['port']};dbname={$db['dbname']}";
            try {
                self::$connection = new PDO($dsn, $db['user'], $db['password']);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::initializeSchema();
            } catch (PDOException $e) {
                die("Database Connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    private static function initializeSchema(): void
    {
        $queries = [
            // Create users table.
            "CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            // Create recipes table.
            "CREATE TABLE IF NOT EXISTS recipes (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                category VARCHAR(100),
                area VARCHAR(100),
                instructions TEXT,
                image VARCHAR(255)
            )",
            // Create ratings table.
            "CREATE TABLE IF NOT EXISTS ratings (
                id SERIAL PRIMARY KEY,
                recipe_id INTEGER REFERENCES recipes(id) ON DELETE CASCADE,
                rating SMALLINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        ];
        foreach ($queries as $query) {
            self::$connection->exec($query);
        }
    }
}
