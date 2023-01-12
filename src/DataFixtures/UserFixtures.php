<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private readonly UserPasswordHasherInterface $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail("admin@crm.com")
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstname("Admin")
            ->setLastname("Admin")
            ->setValidate(true)
            ->setPassword($this->hasher->hashPassword($admin, "Admin2023/"));
        $manager->persist($admin);

        $commercial = new User();
        $commercial->setEmail("jean@crm.com")
            ->setRoles(['ROLE_COMMERCIAL'])
            ->setFirstname("Jean")
            ->setLastname("Jack")
            ->setValidate(true)
            ->setPassword($this->hasher->hashPassword($commercial, "Jean2023/"));
        $manager->persist($commercial);

        $client = new User();
        $client->setEmail("marc@crm.com")
            ->setRoles(['ROLE_USER'])
            ->setFirstname("Marc")
            ->setLastname("Assin")
            ->setValidate(true)
            ->setPassword($this->hasher->hashPassword($client, "Marc2023/"));
        $manager->persist($client);

        $prospect = new User();
        $prospect->setEmail("marc2@crm.com")
            ->setRoles(['ROLE_USER'])
            ->setFirstname("Marc2")
            ->setLastname("Assin")
            ->setValidate(false)
            //->setPassword($this->hasher->hashPassword($prospect, "Marc22023/"))
        ;
        $manager->persist($prospect);

        $manager->flush();
    }
}
