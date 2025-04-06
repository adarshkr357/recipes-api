<?php

namespace App\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\AuthService;

class AuthMiddleware
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function handle(Request $request): ?Response
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return new Response(json_encode(['error' => 'Unauthorized']), 401, ['Content-Type' => 'application/json']);
        }
        $token = $matches[1];
        $payload = $this->authService->validateToken($token);
        if (!$payload) {
            return new Response(json_encode(['error' => 'Invalid token']), 401, ['Content-Type' => 'application/json']);
        }
        $request->attributes->set('user', $payload);
        return null;
    }
}
