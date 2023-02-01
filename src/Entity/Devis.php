<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string|null $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $create_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $last_modification = null;

    #[ORM\ManyToOne(inversedBy: 'devis')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'devisCommercial')]
    private ?User $commercial = null;

    #[ORM\OneToMany(mappedBy: 'devis', targetEntity: ProductInDevis::class, cascade: ['persist', 'remove'])]
    private Collection $contents;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeImmutable $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getLastModification(): ?\DateTimeImmutable
    {
        return $this->last_modification;
    }

    public function setLastModification(\DateTimeImmutable $last_modification): self
    {
        $this->last_modification = $last_modification;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getCommercial(): ?User
    {
        return $this->commercial;
    }

    public function setCommercial(?User $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * @return Collection<int, ProductInDevis>
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(ProductInDevis $productInDevi): self
    {
        if (!$this->contents->contains($productInDevi)) {
            $this->contents->add($productInDevi);
            $productInDevi->setDevis($this);
        }

        return $this;
    }

    public function removeContent(ProductInDevis $productInDevi): self
    {
        if ($this->contents->removeElement($productInDevi)) {
            // set the owning side to null (unless already changed)
            if ($productInDevi->getDevis() === $this) {
                $productInDevi->setDevis(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        $contents = [];
        foreach ($this->getContents() as $content) {
            $contents[] = $content->jsonProduct();
        }

        return array(
            'id' => $this->getId(),
            'create_at' => $this->getCreateAt()->format("c"),
            'last_modification' => $this->getLastModification()->format("c"),
            'client' => $this->getClient()->jsonSerializeEmpty(),
            'commercial' => $this->getCommercial()->jsonSerializeEmpty(),
            'content' => $contents
        );
    }
}
