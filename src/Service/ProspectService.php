<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Core\IProspectService;
use App\Util;
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

    public function create(string $commercialId, array $raw): User
    {
        $commercial = $this->userRepository->find($commercialId);
        if($commercial == null) throw new Exception('no commercial found to create prospect');

        $prospect = new User();
        $prospect->setEmail(Util::tryGet($raw, 'email'))
            ->setRoles(['ROLE_USER'])
            ->setFirstname(Util::tryGet($raw, 'firstname'))
            ->setLastname(Util::tryGet($raw, 'lastname'))
            ->setValidate(false)
            ->setCommercial($commercial);

        $this->userRepository->save($prospect, true);
        return $prospect;
    }

    public function read(string $id): User
    {
        $prospect = $this->userRepository->findOneBy(['id' => $id, 'validate' => false]);
        if($prospect == null) throw new Exception("no prospect found with that id", 404);
        return $prospect;
    }

    public function update(string $id, array $raw): User
    {
        $prospect = $this->userRepository->findOneBy(['id' => $id, 'validate' => false]);
        if($prospect != null) {
            $prospect->setEmail(Util::tryGet($raw, 'email', $prospect->getEmail()))
                ->setFirstname(Util::tryGet($raw, 'firstname', $prospect->getFirstname()))
                ->setLastname(Util::tryGet($raw, 'lastname', $prospect->getLastname()));
            $this->userRepository->save($prospect, true);
            return $prospect;
        } else throw new Exception("no prospect found with that id", 404);
    }
}