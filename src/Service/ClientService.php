<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Service\Core\IClientService;
use Exception;

class ClientService implements IClientService
{
    private readonly UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(): array
    {
        $users = $this->userRepository->findBy(['validate' => true]);
        return array(...array_filter($users, function ($u) {
           return $u->getCommercial() != null;
        }));
    }

    public function getByCommercial(string $commercialId): array
    {
        $user = $this->userRepository->find($commercialId);
        if($user != null) {
            $clientsAndProspect = $user->getClients()->toArray();
            return array(...array_filter($clientsAndProspect, function ($u) {
                return $u->isValidate();
            }));
        } else throw new Exception("no commercial found with that id");
    }
}