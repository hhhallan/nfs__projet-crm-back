<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Core\IUserService;

class UserService implements IUserService
{
    private readonly UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(): array
    {
        return $this->userRepository->findBy(['validate' => true]);
    }

    public function getById(string $id): User
    {
        return $this->userRepository->find($id);
    }
}