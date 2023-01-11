<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code_product = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $plateforme = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductInDevis::class)]
    private Collection $productInDevis;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductInFacture::class)]
    private Collection $productInFactures;

    public function __construct()
    {
        $this->productInDevis = new ArrayCollection();
        $this->productInFactures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeProduct(): ?string
    {
        return $this->code_product;
    }

    public function setCodeProduct(string $code_product): self
    {
        $this->code_product = $code_product;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPlateforme(): ?string
    {
        return $this->plateforme;
    }

    public function setPlateforme(string $plateforme): self
    {
        $this->plateforme = $plateforme;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, ProductInDevis>
     */
    public function getProductInDevis(): Collection
    {
        return $this->productInDevis;
    }

    public function addProductInDevi(ProductInDevis $productInDevi): self
    {
        if (!$this->productInDevis->contains($productInDevi)) {
            $this->productInDevis->add($productInDevi);
            $productInDevi->setProduct($this);
        }

        return $this;
    }

    public function removeProductInDevi(ProductInDevis $productInDevi): self
    {
        if ($this->productInDevis->removeElement($productInDevi)) {
            // set the owning side to null (unless already changed)
            if ($productInDevi->getProduct() === $this) {
                $productInDevi->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductInFacture>
     */
    public function getProductInFactures(): Collection
    {
        return $this->productInFactures;
    }

    public function addProductInFacture(ProductInFacture $productInFacture): self
    {
        if (!$this->productInFactures->contains($productInFacture)) {
            $this->productInFactures->add($productInFacture);
            $productInFacture->setProduct($this);
        }

        return $this;
    }

    public function removeProductInFacture(ProductInFacture $productInFacture): self
    {
        if ($this->productInFactures->removeElement($productInFacture)) {
            // set the owning side to null (unless already changed)
            if ($productInFacture->getProduct() === $this) {
                $productInFacture->setProduct(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array();
    }
}
