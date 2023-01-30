<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class Historic implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string|null $id = null;

    #[ORM\Column(length: 255)]
    private ?string $historic_type = null;

    #[ORM\ManyToOne(inversedBy: 'historics')]
    private ?User $source = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private array $Target = [];

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getHistoricType(): ?string
    {
        return $this->historic_type;
    }

    public function setHistoricType(string $historic_type): self
    {
        $this->historic_type = $historic_type;

        return $this;
    }

    public function getSource(): ?User
    {
        return $this->source;
    }

    public function setSource(?User $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return array();
    }

    public function getTarget(): array
    {
        return $this->Target;
    }

    public function setTarget(array $Target): self
    {
        $this->Target = $Target;

        return $this;
    }
}
