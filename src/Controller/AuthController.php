<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\UserService;
use App\Utils\ResponseHelper;

class AuthController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    // GET /register?username=...&password=...
    public function register(Request $request): Response
    {
        $username = $request->query->get('username');
        $password = $request->query->get('password');

        if (!$username) {
            return ResponseHelper::json(['error' => 'Missing "username" parameter'], 400);
        };
        if (!$password) {
            return ResponseHelper::json(['error' => 'Missing "password" parameter'], 400);
        };

        $data = [
            'username' => $username,
            'password' => $password
        ];
        $user = $this->userService->register($data);
        if (!$user) {
            return ResponseHelper::json(['error' => 'Username already exists'], 409);
        }
        return ResponseHelper::json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    // GET /login?username=...&password=...
    public function login(Request $request): Response
    {
        $username = $request->query->get('username');
        $password = $request->query->get('password');
        if (!$username) {
            return ResponseHelper::json(['error' => 'Missing "username" parameter'], 400);
        };
        if (!$password) {
            return ResponseHelper::json(['error' => 'Missing "password" parameter'], 400);
        };
        $data = [
            'username' => $username,
            'password' => $password
        ];
        $token = $this->userService->login($data);
        if (!$token) {
            return ResponseHelper::json(['error' => 'Invalid credentials'], 401);
        }
        return ResponseHelper::json(['message' => 'Login successful', 'token' => $token], 200);
    }
}
