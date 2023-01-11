<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $price = random_int(3000, 8000) / 100;
        foreach (array('PlayStation 4', 'PlayStation 5', 'Xbox One', 'Xbox Series X/S', 'PC') as $plateforme) {
            $product = new Product();
            $product->setName('Elden Ring (2022)')
                ->setPlateforme($plateforme)
                ->setCodeProduct('')
                ->setArchived(false)
                ->setPrice($price)
                ->setImage('https://media.senscritique.com/media/000020907829/300/elden_ring.jpg');

            $manager->persist($product);
        }

        $price = random_int(3000, 8000) / 100;
        foreach (array('PlayStation 4', 'PlayStation 5') as $plateforme) {
            $product = new Product();
            $product->setName('God of War: Ragnarök (2022)')
                ->setPlateforme($plateforme)
                ->setCodeProduct('')
                ->setArchived(false)
                ->setPrice($price)
                ->setImage('https://media.senscritique.com/media/000020893527/300/god_of_war_ragnarok.jpg');

            $manager->persist($product);
        }

        $price = random_int(3000, 8000) / 100;
        foreach (array('PlayStation 4', 'PlayStation 5', 'PC') as $plateforme) {
            $product = new Product();
            $product->setName('Stray (2022)')
                ->setPlateforme($plateforme)
                ->setCodeProduct('')
                ->setArchived(false)
                ->setPrice($price)
                ->setImage('https://media.senscritique.com/media/000020871725/300/stray.jpg');

            $manager->persist($product);
        }

        $price = random_int(3000, 8000) / 100;
        foreach (array('PC', 'Mac', 'Xbox One', 'Xbox Series X/S', 'PlayStation 4', 'PlayStation 5', 'Nintendo Switch') as $plateforme) {
            $product = new Product();
            $product->setName('Tunic (2022)')
                ->setPlateforme($plateforme)
                ->setCodeProduct('')
                ->setArchived(false)
                ->setPrice($price)
                ->setImage('https://media.senscritique.com/media/000020906038/300/tunic.jpg');

            $manager->persist($product);
        }

        $price = random_int(3000, 8000) / 100;
        foreach (array('PlayStation 4', 'PlayStation 5') as $plateforme) {
            $product = new Product();
            $product->setName('Horizon: Forbidden West (2022)')
                ->setPlateforme($plateforme)
                ->setCodeProduct('')
                ->setArchived(false)
                ->setPrice($price)
                ->setImage('https://media.senscritique.com/media/000020943582/300/horizon_forbidden_west.jpg');

            $manager->persist($product);
        }

        $price = random_int(3000, 8000) / 100;
        foreach (array('Nintendo Switch') as $plateforme) {
            $product = new Product();
            $product->setName('Légendes Pokémon : Arceus (2022)')
                ->setPlateforme($plateforme)
                ->setCodeProduct('')
                ->setArchived(false)
                ->setPrice($price)
                ->setImage('https://media.senscritique.com/media/000020607855/300/legendes_pokemon_arceus.jpg');

            $manager->persist($product);
        }

        $price = random_int(3000, 8000) / 100;
        foreach (array('PC', 'Xbox Series X/S', 'PlayStation 5', 'Nintendo Switch') as $plateforme) {
            $product = new Product();
            $product->setName('A Plague Tale: Requiem (2022)')
                ->setPlateforme($plateforme)
                ->setCodeProduct('')
                ->setArchived(false)
                ->setPrice($price)
                ->setImage('https://media.senscritique.com/media/000020888298/300/a_plague_tale_requiem.jpg');

            $manager->persist($product);
        }

        $manager->flush();
    }
}
