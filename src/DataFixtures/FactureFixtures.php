<?php

namespace App\DataFixtures;

use App\Entity\Facture;
use App\Entity\ProductInFacture;
use App\Service\Core\IClientService;
use App\Service\Core\ICommercialService;
use App\Service\Core\IProductService;
use App\Service\Core\IProspectService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FactureFixtures extends Fixture implements DependentFixtureInterface
{
    private readonly IProductService $productService;
    private readonly IClientService $clientService;
    private readonly IProspectService $prospectService;
    private readonly ICommercialService $commercialService;

    public function __construct(IProductService $productService, IClientService $clientService, IProspectService $prospectService, ICommercialService $commercialService)
    {
        $this->prospectService = $prospectService;
        $this->productService = $productService;
        $this->clientService =$clientService;
        $this->commercialService = $commercialService;
    }

    public function load(ObjectManager $manager): void
    {
        $states = array("DRAFT", "VALIDATE", "PAYED");
        $products = $this->productService->getAll();
        $clients = array_merge($this->clientService->getAll(), $this->prospectService->getAll());
        $commercials = $this->commercialService->getAll();

        $nbFacture = 20;
        for ($i = 0; $i < $nbFacture; $i++) {
            $date = new DateTimeImmutable("-".rand(1,8)." days");
            $facture = new Facture();
            $facture->setStat($states[rand(0,2)])
                ->setCommercial($commercials[array_rand($commercials)])
                ->setClient($clients[array_rand($clients)])
                ->setCreateAt($date)
                ->setLastModification($date);

            $nbContent = rand(1,3);
            for ($j = 0; $j < $nbContent; $j++) {
                $productInFacture = new ProductInFacture();
                $productInFacture->setQuantity(rand(1,10))
                    ->setProduct($products[array_rand($products)]);

                $facture->addContent($productInFacture);
            }
            $manager->persist($facture);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            UserFixtures::class,
        ];
    }
}
