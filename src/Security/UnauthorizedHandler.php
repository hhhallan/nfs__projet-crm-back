<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class UnauthorizedHandler implements AuthenticationEntryPointInterface
{

    public function start(Request $request, AuthenticationException $authException = null): JsonResponse
    {
        $response = [
            "error" => "Unauthorized",
            "status_code" => 401,
            "message" => "Authentication failed. Please provide a valid token."
        ];
        return new JsonResponse($response, 401);
    }
}