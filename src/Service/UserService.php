<?php

namespace App\Service;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserService
{
    private $userRepository;
    protected $jwtSecret;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->jwtSecret = getenv('JWT_SECRET') ?: 'yoursecretkey';
    }

    // Registers a new user. Returns user data or null if username exists.
    public function register(array $data): ?array
    {
        // Check if a user with that username already exists.
        $existing = $this->userRepository->findByUsername($data['username']);
        if ($existing) {
            return null;
        }
        return $this->userRepository->createUser($data);
    }

    // Logs in a user and returns a JWT token, or null on failure.
    public function login(array $data): ?string
    {
        $userData = $this->userRepository->findByUsername($data['username']);
        if (!$userData) {
            return null;
        }
        if (password_verify($data['password'], $userData['password'])) {
            $payload = [
                'sub' => $userData['id'],
                'iat' => time(),
                'exp' => time() + 3600, // token valid for one hour
            ];
            return JWT::encode($payload, $this->jwtSecret, 'HS256');
        }
        return null;
    }

    public function getUserById($id): ?array
    {
        return $this->userRepository->findById($id);
    }
}
