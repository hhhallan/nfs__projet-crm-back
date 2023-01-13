<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Core\IClientService;
use App\Util;
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

    public function create(string $prospectId): User
    {
        $newUser = $this->userRepository->findOneBy(['validate' => false, 'id' => $prospectId]);
        if($newUser != null) {
            $newUser->setValidate(true);
            $this->userRepository->save($newUser, true);
            return $newUser;
            // TODO : envoy mail pour mettre mdp
        } else throw new Exception("no prospect found to create client", 404);
    }

    public function read(string $id): User
    {
        $client = $this->userRepository->findOneBy(['id' => $id, 'validate' => true]);
        if($client == null) throw new Exception("no client found with that id", 404);
        return $client;
    }

    public function update(string $id, array $raw): User
    {
        $client = $this->userRepository->findOneBy(['id' => $id, 'validate' => true]);
        if($client != null) {
            $client->setEmail(Util::tryGet($raw, 'email', $client->getEmail()))
                ->setFirstname(Util::tryGet($raw, 'firstname', $client->getFirstname()))
                ->setLastname(Util::tryGet($raw, 'lastname', $client->getLastname()));
            $this->userRepository->save($client, true);
            return $client;
        } else throw new Exception("no client found with that id", 404);
    }
}