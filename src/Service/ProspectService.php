<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Service\Core\IProspectService;
use Exception;

class ProspectService implements IProspectService
{
    private readonly UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(): array
    {
        return $this->userRepository->findBy(['validate' => false]);
    }

    public function getByCommercial(string $commercialId): array
    {
        $user = $this->userRepository->find($commercialId);
        if($user != null) {
            $clientsAndProspect = $user->getClients()->toArray();
            return array(...array_filter($clientsAndProspect, function ($u) {
               return $u->isValidate() == false;
            }));
        } else throw new Exception("no commercial found with that id");
    }
}