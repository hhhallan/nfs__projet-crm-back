<?php

namespace App\Event;

use App\Entity\Devis;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CreateDevisEvent extends Event
{
    public const NAME = 'devis.create';
    private Devis $devis;
    private User $requester;

    public function __construct(Devis $devis, User $requester)
    {
        $this->devis = $devis;
        $this->requester = $requester;
    }

    public function getDevis(): Devis
    {
        return $this->devis;
    }

    public function getRequester(): User
    {
        return $this->requester;
    }
}