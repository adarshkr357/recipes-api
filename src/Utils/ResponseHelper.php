<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    public static function json($data, $status = 200): Response
    {
        return new Response(json_encode($data, JSON_PRETTY_PRINT), $status, [
            'Content-Type' => 'application/json'
        ]);
    }
}
