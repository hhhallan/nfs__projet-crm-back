<?php

namespace App\Controller;

use App\Service\Core\IUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class UserController extends AbstractController
{
    private readonly IUserService $userService;
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/user', name: 'app_user_list')]
    public function list(): JsonResponse
    {
        $users = $this->userService->getAll();
        return $this->json(array_map(function ($u) {
            return $u->jsonDetails();
        }, $users));
    }
}
