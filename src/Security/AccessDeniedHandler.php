<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?JsonResponse
    {
        $response = [
            "error" => "Access Denied",
            "status_code" => 403,
            "message" => "You do not have sufficient permissions to access this resource."
        ];
        return new JsonResponse($response, 403);
    }
}