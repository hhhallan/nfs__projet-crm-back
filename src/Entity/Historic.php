<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class Historic implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $historic_type = null;

    #[ORM\ManyToOne(inversedBy: 'historics')]
    private ?User $source = null;

    #[ORM\Column(type: 'uuid')]
    private ?Uuid $target_id = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    public function getId(): ?int
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

    public function getTargetId(): ?Uuid
    {
        return $this->target_id;
    }

    public function setTargetId(Uuid $target_id): self
    {
        $this->target_id = $target_id;

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

    public function jsonSerialize(): array
    {
        return array();
    }
}
