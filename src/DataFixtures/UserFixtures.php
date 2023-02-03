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
        $this->name_cli = array(
            "Sam Rowland",
            "Gavin Schmidt",
            "Hasan Cummings",
            "Sadia Cline",
            "Kiran Case",
            "Kiera Curtis",
            "Maria Salinas",
            "Faith Welsh",
            "Justin Mathis",
            "Kyla Jackson",
            "Mollie Caldwell",
            "Miles Holloway",
            "Kaitlyn Harrington",
            "Marc Dalton",
            "Arjan Molina",
            "Nettie Krueger",
            "Herbert Ibarra",
            "Zackary Doyle",
            "Gabrielle Keith",
            "Yasir Huff",
            "Remi Holmes",
            "Tony Schneider",
            "Miah Fuentes",
            "Saarah Jefferson",
            "Oscar Barlow",
            "Malakai Clayton",
            "Brodie Love",
            "Herbie Chapman",
            "Sofia Mahoney",
            "Anas Roberts",
            "Phyllis Flynn",
            "Gianluca Mckee",
            "Aminah Curry",
            "Russell Ferguson",
            "Zachery Sharp",
            "Archibald Zuniga",
            "Brandon Cortez",
            "Kirsten Robbins",
            "Kamal Ross",
            "Allan Calhoun",
            "Fay Anderson",
            "Carlo Villa",
            "Evelyn Barber",
            "Zoe Dunlap",
            "Marcel Becker",
            "Cora O'Neill",
            "Scarlett Solomon",
            "Ronald Contreras",
            "Mathilda Andersen",
            "Alasdair Carroll",
            "Sion Matthews",
            "Eloise Donovan",
            "Frances Terry",
            "Nicola Sherman",
            "Violet Dunn",
            "Abby Phillips",
            "Sean Hilton",
            "Maliha May",
            "Lawson Barnes",
            "Logan Lang",
            "Silas Rodriguez",
            "Duncan Cain",
            "Vinny Copeland",
            "Aamina West",
            "Helena Sims",
            "Evie Graves",
            "Amie Walters",
            "Szymon Garrison",
            "Karol Waters"
        );
        $this->name_pro = array(
            "Neve Maddox",
            "Damian Mcclure",
            "Ines Galvan",
            "Declan Frederick",
            "Martin Odom",
            "Denzel Freeman",
            "Jonty Barrett",
            "Mario Holland",
            "Sumaya Love",
            "Lola Banks",
            "Bernice Mckee",
            "Sabrina Patterson",
            "Subhan Ayers",
            "Alexis Barker",
            "Lucian Koch",
            "Ayesha Newman",
            "Mateo Mcknight",
            "Zakariya Cantrell",
            "Saad Acevedo",
            "Faith Crosby",
            "Mahnoor Ali",
            "Harriet Reynolds",
            "Keyaan Christian",
            "Belle Brandt",
            "Cyrus Young",
            "Kaine Padilla",
            "Sebastian Oconnell",
            "Heidi Price",
            "Olivier Guzman",
            "Phyllis Delgado",
            "Georgie Mckinney",
            "Flora Knight",
            "Anita Yang",
            "Saba Olson",
            "Kasey Douglas",
            "Robert Petty",
            "Caleb Holt",
            "Kobi Pollard",
            "Idris Arias",
            "Dylan Mckay",
            "Emilio Monroe",
            "Velma Stewart",
            "Abubakar Humphrey",
            "Jac Gilbert",
            "Abigail Brewer",
            "Anisha Bullock",
            "Hajra Rowland",
            "Kiana Pena",
            "Melissa Whitehead",
            "Elisabeth Meadows",
            "Vinny Ramos",
            "Rita Harrison",
            "Freyja Joyce",
            "Harry Shepard",
            "Isra Curry",
            "Jorge Estrada",
            "Malik Mccall",
            "Roosevelt Santos",
            "Osian Hansen",
            "Howard Campos",
            "Gwen Gutierrez",
            "Gabrielle Cox",
            "Eva King",
            "Kezia Richard",
            "Ismail Bernard",
            "Gracie Crane",
            "Edwin Villegas",
            "Mildred Bolton",
            "Sean Simmons",
        );
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
