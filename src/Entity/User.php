<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string|null $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?bool $validate = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'clients')]
    private ?self $commercial = null;

    #[ORM\OneToMany(mappedBy: 'commercial', targetEntity: self::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Devis::class)]
    private Collection $devis;

    #[ORM\OneToMany(mappedBy: 'commercial', targetEntity: Devis::class)]
    private Collection $devisCommercial;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Facture::class)]
    private Collection $factures;

    #[ORM\OneToMany(mappedBy: 'commercial', targetEntity: Facture::class)]
    private Collection $facturesCommercial;

    #[ORM\OneToMany(mappedBy: 'source', targetEntity: History::class)]
    private Collection $histories;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $resetExpire = null;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->devisCommercial = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->facturesCommercial = new ArrayCollection();
        $this->histories = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function isValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function getCommercial(): ?self
    {
        return $this->commercial;
    }

    public function setCommercial(?self $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(self $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setCommercial($this);
        }

        return $this;
    }

    public function removeClient(self $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCommercial() === $this) {
                $client->setCommercial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): self
    {
        if (!$this->devis->contains($devi)) {
            $this->devis->add($devi);
            $devi->setClient($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): self
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getClient() === $this) {
                $devi->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevisCommercial(): Collection
    {
        return $this->devisCommercial;
    }

    public function addDevisCommercial(Devis $devisCommercial): self
    {
        if (!$this->devisCommercial->contains($devisCommercial)) {
            $this->devisCommercial->add($devisCommercial);
            $devisCommercial->setCommercial($this);
        }

        return $this;
    }

    public function removeDevisCommercial(Devis $devisCommercial): self
    {
        if ($this->devisCommercial->removeElement($devisCommercial)) {
            // set the owning side to null (unless already changed)
            if ($devisCommercial->getCommercial() === $this) {
                $devisCommercial->setCommercial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFacturesCommercial(): Collection
    {
        return $this->facturesCommercial;
    }

    public function addFacturesCommerical(Facture $facturesCommerical): self
    {
        if (!$this->facturesCommercial->contains($facturesCommerical)) {
            $this->facturesCommercial->add($facturesCommerical);
            $facturesCommerical->setCommercial($this);
        }

        return $this;
    }

    public function removeFacturesCommerical(Facture $facturesCommerical): self
    {
        if ($this->facturesCommercial->removeElement($facturesCommerical)) {
            // set the owning side to null (unless already changed)
            if ($facturesCommerical->getCommercial() === $this) {
                $facturesCommerical->setCommercial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistoric(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories->add($history);
            $history->setSource($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getSource() === $this) {
                $history->setSource(null);
            }
        }

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getResetExpire(): ?\DateTimeImmutable
    {
        return $this->resetExpire;
    }

    public function setResetExpire(?\DateTimeImmutable $resetExpire): self
    {
        $this->resetExpire = $resetExpire;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $res = array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
        );

        if(in_array('ROLE_ADMIN', $this->getRoles())) {
            $res['type'] = "ADMIN";
        } else if(in_array('ROLE_COMMERCIAL', $this->getRoles())) {
            $res['type'] = "COMMERCIAL";
            $res['clients'] = array_map(function ($u) { return $u->jsonSerializeEmpty();}, $this->getClients()->toArray());
            $res['devis_realise'] = $this->getDevisCommercial()->toArray();
            $res['factures_realise'] = $this->getFacturesCommercial()->toArray();
        } else if($this->isValidate()) {
            $res['type'] = "CLIENT";
            $res['commercial'] = $this->getCommercial()->jsonSerializeEmpty();
            $res['devis'] = $this->getDevis()->toArray();
            $res['factures'] = $this->getFactures()->toArray();
        } else {
            $res['type'] = "PROSPECT";
            $res['commercial'] = $this->getCommercial()->jsonSerializeEmpty();
            $res['devis'] = $this->getDevis()->toArray();
        }
        return $res;
    }

    public function jsonSerializeDetails(): array
    {
        $res = $this->jsonSerialize();
        $res['history'] = $this->getHistories()->toArray();
        return $res;
    }

    public function jsonSerializeEmpty(): array
    {
        $res = array(
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
        );

        if(in_array('ROLE_ADMIN', $this->getRoles())) {
            $res['type'] = "ADMIN";
        } else if(in_array('ROLE_COMMERCIAL', $this->getRoles())) {
            $res['type'] = "COMMERCIAL";
        } else if($this->isValidate()) {
            $res['type'] = "CLIENT";
        } else {
            $res['type'] = "PROSPECT";
        }
        return $res;
    }
}
