<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $create_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $last_modification = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'facturesCommerical')]
    private ?User $commercial = null;

    #[ORM\Column(length: 255)]
    private ?string $stat = null;

    #[ORM\OneToMany(mappedBy: 'facture', targetEntity: ProductInFacture::class)]
    private Collection $contents;

    public function __construct()
    {
        $this->contents = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getStat(): ?string
    {
        return $this->stat;
    }

    public function setStat(string $stat): self
    {
        $this->stat = $stat;

        return $this;
    }

    /**
     * @return Collection<int, ProductInFacture>
     */
    public function getContents(): Collection
    {
        return $this->contents;
    }

    public function addContent(ProductInFacture $content): self
    {
        if (!$this->contents->contains($content)) {
            $this->contents->add($content);
            $content->setFacture($this);
        }

        return $this;
    }

    public function removeContent(ProductInFacture $content): self
    {
        if ($this->contents->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getFacture() === $this) {
                $content->setFacture(null);
            }
        }

        return $this;
    }
}
