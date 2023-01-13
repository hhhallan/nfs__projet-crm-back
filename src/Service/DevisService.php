<?php

namespace App\Service;

use App\Entity\Devis;
use App\Entity\ProductInDevis;
use App\Repository\DevisRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\Core\IDevisService;
use App\Util;
use DateTimeImmutable;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Exception;

class DevisService implements IDevisService
{
    private readonly DevisRepository $devisRepository;
    private readonly UserRepository $userRepository;
    private readonly ProductRepository $productRepository;
    public function __construct(DevisRepository $devisRepository, UserRepository $userRepository, ProductRepository $productRepository)
    {
        $this->devisRepository = $devisRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
    }

    public function getAll(): array
    {
        return $this->devisRepository->findAll();
    }

    public function getByCommercial(string $commercialId): array
    {
        $user = $this->userRepository->find($commercialId);
        if($user != null) {
            return $user->getDevisCommercial()->toArray();
        } else throw new Exception("no commercial found with that id");
    }

    public function getByClient(string $clientId): array
    {
        $user = $this->userRepository->find($clientId);
        if($user != null) {
            return $user->getDevis()->toArray();
        } else throw new Exception("no client found with that id");
    }

    public function create(array $raw): Devis
    {
        $client = $this->userRepository->find(Util::tryGet($raw, 'client_id'));
        $commercial = $this->userRepository->find(Util::tryGet($raw, 'commercial_id'));

        if($client != null && ($client->jsonSerialize()['type'] == 'CLIENT' || $client->jsonSerialize()['type'] == 'PROSPECT')) {
            if($commercial != null && $commercial->jsonSerialize()['type'] == 'COMMERCIAL') {
                $now = new DateTimeImmutable('now');
                $devis = new Devis();
                $devis->setClient($client)
                    ->setCommercial($commercial)
                    ->setCreateAt($now)
                    ->setLastModification($now);

                foreach (Util::tryGet($raw, 'contents', array()) as $rawContent) {
                    $quantity = Util::tryGet($rawContent, 'quantity');
                    $product = $this->productRepository->find(Util::tryGet($rawContent, 'product_id'));

                    if($product != null && $quantity != null) {
                        $productInDevis = new ProductInDevis();
                        $productInDevis->setQuantity($quantity)
                            ->setProduct($product);
                        $devis->addContent($productInDevis);
                    }
                }

                if(count($devis->getContents()) > 0) {
                    $this->devisRepository->save($devis, true);
                    return $devis;
                }else throw new Exception('devis need some product and quantity', 400);
            } else throw new Exception('No commercial found with that id', 404);
        } else throw new Exception('No client found with that id', 404);

    }

    public function update(string $id, array $raw): Devis
    {
        throw new FeatureNotImplemented();
    }
}