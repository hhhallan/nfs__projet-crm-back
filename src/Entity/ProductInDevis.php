<?php

namespace App\Entity;

use App\Repository\ProductInDevisRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: ProductInDevisRepository::class)]
class ProductInDevis
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string|null $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'productInDevis')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'contents')]
    private ?Devis $devis = null;

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

    public function getDevis(): ?Devis
    {
        return $this->devis;
    }

    public function setDevis(?Devis $devis): self
    {
        $this->devis = $devis;

        return $this;
    }

    public function jsonDevis(): array
    {
        return array(
            'quantity' => $this->getQuantity(),
            'devis' => $this->getDevis()
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
