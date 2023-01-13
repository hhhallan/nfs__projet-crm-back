<?php

namespace App\Entity;

use App\Repository\ProductInFactureRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ProductInFactureRepository::class)]
class ProductInFacture
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string|null $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'productInFactures')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'contents')]
    private ?Facture $facture = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    public function jsonFacture(): array
    {
        return array(
            'quantity' => $this->getQuantity(),
            'devis' => $this->getFacture()
        );
    }


    public function jsonProduct(): array
    {
        return array(
            'quantity' => $this->getQuantity(),
            'product' => $this->getProduct()
        );
    }
}
