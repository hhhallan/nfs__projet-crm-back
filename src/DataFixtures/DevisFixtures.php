<?php

namespace App\DataFixtures;

use App\Entity\Devis;
use App\Entity\ProductInDevis;
use App\Service\Core\IClientService;
use App\Service\Core\ICommercialService;
use App\Service\Core\IProductService;
use App\Service\Core\IProspectService;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DevisFixtures extends Fixture implements DependentFixtureInterface
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
        $products = $this->productService->getAll();
        $clients = array_merge($this->clientService->getAll(), $this->prospectService->getAll());
        $commercials = $this->commercialService->getAll();

        $nbDevis = 400;
        for ($i = 0; $i < $nbDevis; $i++) {
            $date = new DateTimeImmutable("-".rand(10,300)." days");
            $devis = new Devis();
            $devis->setCommercial($commercials[array_rand($commercials)])
                ->setClient($clients[array_rand($clients)])
                ->setCreateAt($date)
                ->setLastModification($date);

            $nbContent = rand(1,3);
            for ($j = 0; $j < $nbContent; $j++) {
                $productInDevis = new ProductInDevis();
                $productInDevis->setQuantity(rand(1,10))
                    ->setProduct($products[array_rand($products)]);

                $devis->addContent($productInDevis);
            }
            $manager->persist($devis);
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
