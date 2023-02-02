<?php

namespace App\Service;

use App\Entity\Facture;
use App\Entity\ProductInDevis;
use App\Entity\ProductInFacture;
use App\Repository\DevisRepository;
use App\Repository\FactureRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\Core\IFactureService;
use App\Util;
use DateTimeImmutable;
use Exception;

class FactureService implements IFactureService
{
    private readonly DevisRepository $devisRepository;
    private readonly UserRepository $userRepository;
    private readonly ProductRepository $productRepository;
    private readonly FactureRepository $factureRepository;
    public function __construct(DevisRepository $devisRepository, UserRepository $userRepository, ProductRepository $productRepository, FactureRepository $factureRepository)
    {
        $this->devisRepository = $devisRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->factureRepository = $factureRepository;
    }

    public function getAll(): array
    {
        return $this->factureRepository->findAll();
    }

    /**
     * @throws Exception
     */
    public function getByClient(string $id): array
    {
        $client = $this->userRepository->find($id);
        if($client != null) {
            return $client->getFactures()->toArray();
        } else throw new Exception("no client found with that id", 404);
    }

    /**
     * @throws Exception
     */
    public function getByCommercial(string $id): array
    {
        $commercial = $this->userRepository->find($id);
        if($commercial != null) {
            return $commercial->getFacturesCommerical()->toArray();
        } else throw new Exception("no commercial found with that id", 404);
    }

    /**
     * @throws Exception
     */
    public function create(string $devisId): Facture
    {
        $devis = $this->devisRepository->find($devisId);
        if($devis != null) {
            $date = new DateTimeImmutable('now');

            $facture = new Facture();
            $facture->setClient($devis->getClient())
                ->setCommercial($devis->getCommercial())
                ->setCreateAt($date)
                ->setLastModification($date)
                ->setStat("DRAFT");

            foreach ($devis->getContents() as $content) {
                $factureContent = new ProductInFacture();
                $factureContent->setProduct($content->getProduct())
                    ->setQuantity($content->getQuantity());

                $facture->addContent($factureContent);
            }

            if(count($facture->getContents()) > 0) {
                $this->factureRepository->save($facture, true);
                return $facture;
            }else throw new Exception('facture need some product and quantity', 400);
        }else throw new Exception("no devis found with that id", 404);
    }

    /**
     * @throws Exception
     */
    public function read(string $id): Facture
    {
        $facture = $this->factureRepository->find($id);
        if($facture != null) {
            return $facture;
        } else throw new Exception("no facture found with that id", 404);
    }

    /**
     * @throws Exception
     */
    public function update(string $id, array $raw): Facture
    {
        $facture = $this->factureRepository->find($id);
        if($facture != null) {
            if($facture->getStat() == "DRAFT") {

                foreach ($facture->getContents() as $content) {
                    $facture->removeContent($content);
                }

                foreach ($raw as $rawContent) {
                    $quantity = Util::tryGet($rawContent, 'quantity');
                    $product = $this->productRepository->find(Util::tryGet($rawContent, 'product_id'));

                    if ($product != null && $quantity != null) {
                        $productInFacture = new ProductInFacture();
                        $productInFacture->setQuantity($quantity)
                            ->setProduct($product);
                        $facture->addContent($productInFacture);
                    }
                }

                if (count($facture->getContents()) > 0) {
                    $facture->setLastModification(new DateTimeImmutable('now'));
                    $this->factureRepository->save($facture, true);
                    return $facture;
                } else throw new Exception('facture need some product and quantity', 400);
            } else throw new Exception("this facture is not editable", 403);
        } else throw new Exception("no facture found with that id", 404);
    }

    /**
     * @throws Exception
     */
    public function changeState(string $id, string $state): Facture
    {
        $facture = $this->factureRepository->find($id);
        if($facture != null) {
            $facture->setStat($state);
            $this->factureRepository->save($facture, true);
            return $facture;
        } else throw new Exception("no facture found with that id", 404);
    }

    /**
     * @throws Exception
     */
    //envoie de mail de la facture en pdf au client
    public function sendMail(string $id): Facture
    {
        $facture = $this->factureRepository->find($id);
        if($facture != null) {
            $client = $facture->getClient();
            $email = $client->getEmail();
            $name = $client->getFirstName() . " " . $client->getLastName();
            $subject = "Facture n°" . $facture->getId();
            $message = "Bonjour " . $name . ",\n\nVous trouverez ci-joint la facture n°" . $facture->getId() . ".\n\nCordialement,\n" . $facture->getCommercial()->getFirstName() . " " . $facture->getCommercial()->getLastName();
            $pdf = $this->factureRepository->generatePdf($facture);
            $this->factureRepository->sendMail($email, $name, $subject, $message, $pdf);
            return $facture;
        } else throw new Exception("no facture found with that id", 404);
    }
}