<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class AuthController extends AbstractController
{
    private readonly AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[Route('/reset', name: 'app_reset', methods: 'PUT')]
    public function resetEmail(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $this->authService->resetPassword(Util::tryGet($body, 'email'));
        return $this->json(['status' => 'success', 'message' => 'an email has been send']);
    }

    #[Route('/changePwd/{token}', name: 'app_change', methods: 'PUT')]
    public function changePwd(string $token, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        if($this->authService->changePassword($token, Util::tryGet($body, 'password'))) {
            return $this->json(['status' => 'success', 'message' => 'password has been change']);
        }else{
            return $this->json(['status' => 'success', 'message' => 'error please retry'], 400);
        }
    }
}
