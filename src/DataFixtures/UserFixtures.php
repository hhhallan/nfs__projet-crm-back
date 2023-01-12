<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private readonly UserPasswordHasherInterface $hasher;
    private readonly array $name_com;
    private readonly array $name_cli;
    private readonly array $name_pro;


    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
        $this->name_com = array("Caitlyn Lewis", "Agnes Knowles", "Kain Villegas", "Ted Middleton", "Enzo Montoya");
        $this->name_cli = array("Sam Rowland", "Amber Faulkner", "Alyssia Cotton", "Stephen Flynn", "Siena Kent", "Alessandro Case", "Skyla Bradshaw", "Scarlett Richardson", "Hana Morris", "Tori Salinas");
        $this->name_pro = array("Mark Meyers", "Ross Francis", "Byron Payne", "Jerome Greene", "Finnley Wade", "Holly Morgan", "Lina Mullen", "Wesley Wiggins", "Jasmin Savage", "Alistair Pruitt");
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

        $commercials = array();
        foreach ($this->name_com as $com_name) {
            $commercial = new User();
            $commercial->setEmail(str_replace(' ', '.', $com_name)."@crm.com")
                ->setRoles(['ROLE_COMMERCIAL'])
                ->setFirstname(explode(' ', $com_name)[0])
                ->setLastname(explode(' ', $com_name)[1])
                ->setValidate(true)
                ->setPassword($this->hasher->hashPassword($commercial, explode(' ', $com_name)[0] ."2023/"));
            $manager->persist($commercial);
            $commercials[] = $commercial;
        }


        foreach ($this->name_cli as $client_name) {
            $client = new User();
            $client->setEmail(str_replace(' ', '.', $client_name)."@gmail.com")
                ->setRoles(['ROLE_USER'])
                ->setFirstname(explode(' ', $client_name)[0])
                ->setLastname(explode(' ', $client_name)[1])
                ->setValidate(true)
                ->setPassword($this->hasher->hashPassword($client, explode(' ', $client_name)[0] ."2023/"))
                ->setCommercial($commercials[array_rand($commercials)]);
            $manager->persist($client);
        }

        foreach ($this->name_pro as $pro_name) {
            $prospect = new User();
            $prospect->setEmail(str_replace(' ', '.', $pro_name)."@gmail.com")
                ->setRoles(['ROLE_USER'])
                ->setFirstname(explode(' ', $pro_name)[0])
                ->setLastname(explode(' ', $pro_name)[1])
                ->setValidate(false)
                ->setCommercial($commercials[array_rand($commercials)]);
            $manager->persist($prospect);
        }

        $manager->flush();
    }
}
