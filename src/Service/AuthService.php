<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Util;
use DateTimeImmutable;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthService
{
    private readonly TokenStorageInterface $tokenStorage;
    private readonly UserRepository $userRepository;
    private readonly UserPasswordHasherInterface $hasher;
    public function __construct(UserRepository $userRepository, TokenStorageInterface $storage, UserPasswordHasherInterface $hasher)
    {
        $this->tokenStorage = $storage;
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function getUser(): User
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof TokenInterface) {
            /** @var User $user */
            $user = $token->getUser();
            return $user;
        } else throw new Exception("no user found");
    }

    public function resetPassword(?string $email, bool $exist = true): bool
    {
        $user = $this->userRepository->findOneBy(['email' => $email, 'validate' => $exist]);
        if($user != null) {
            $token = Util::generateToken();
            $user->setResetToken($token)
                ->setResetExpire(new DateTimeImmutable('+1 hours'));

            $this->userRepository->save($user, true);
            // TODO : envoie d'un email avec le lien pour changer son mot de passe (lien du front ave le token a mettre dans le mail)
            var_dump($token);
            return true;
        }
        return false;
    }

    public function changePassword(string $token, string $password): bool
    {
        $user = $this->userRepository->findOneBy(['resetToken' => $token]);
        if($user != null) {
            $user->setPassword($this->hasher->hashPassword($user, $password))
                ->setResetToken(null)
                ->setResetExpire(null);

            $this->userRepository->save($user, true);
            return true;
        }
        return false;
    }
}