<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Service\Core\ICommercialService;

class CommercialService implements ICommercialService
{
    private readonly UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(): array
    {
        return array_filter($this->userRepository->findBy(['validate' => true]), function ($u) {
            return in_array('ROLE_COMMERCIAL', $u->getRoles()) && !in_array('ROLE_ADMIN', $u->getRoles());
        });
    }
}