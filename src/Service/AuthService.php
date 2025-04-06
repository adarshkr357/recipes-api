<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    protected $jwtSecret;

    public function __construct()
    {
        // Retrieve from environment or config
        $this->jwtSecret = getenv('JWT_SECRET') ?: 'yoursecretkey';
    }

    // Generate a JWT token for a given user ID.
    public function generateToken($userId): string
    {
        $payload = [
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + 3600, // Valid for 1 hour.
        ];
        return JWT::encode($payload, $this->jwtSecret, 'HS256');
    }

    // Validate a JWT token; returns decoded payload if valid or null.
    public function validateToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
}
