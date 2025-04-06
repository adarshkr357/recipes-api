<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from the .env file in the project root
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// If running tests locally, override the DB host if needed.
$dbHost = getenv('DB_HOST') ?: 'postgres';
$dbPort = $_ENV['DB_PORT'] ?? 5432;
$dbName = $_ENV['DB_NAME'] ?? 'hellofresh';
$dbUser = $_ENV['DB_USER'] ?? 'hellofresh';
$dbPass = $_ENV['DB_PASS'] ?? 'hellofresh';

// This is for my local machine
if (php_sapi_name() === 'cli') {
    $dbHost = 'localhost';
    $dbName = 'postgres';
    $dbUser = 'postgres';
    $dbPass = 'pass';
}

return [
    'db' => [
        'host'     => $dbHost,
        'port'     => $dbPort,
        'dbname'   => $dbName,
        'user'     => $dbUser,
        'password' => $dbPass
    ],
    'jwt' => [
        'secret'     => $_ENV['JWT_SECRET'] ?? 'somesecretkey',
        'issuer'     => $_ENV['JWT_ISSUER'] ?? 'http://localhost',
        'audience'   => $_ENV['JWT_AUDIENCE'] ?? 'http://localhost',
        'expiration' => intval($_ENV['JWT_EXPIRATION'] ?? 3600),
    ],
];
