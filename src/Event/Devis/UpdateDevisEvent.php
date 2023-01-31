<?php

namespace App\Event\Devis;

use App\Entity\Devis;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateDevisEvent extends Event
{
    public const NAME = 'devis.update';
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