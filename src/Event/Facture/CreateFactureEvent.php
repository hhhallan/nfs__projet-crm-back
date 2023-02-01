<?php

namespace App\Event\Facture;

use App\Entity\Facture;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CreateFactureEvent extends Event
{
    public const NAME = 'facture.create';
    private Facture $facture;
    private User $requester;

    public function __construct(Facture $facture, User $requester)
    {
        $this->facture = $facture;
        $this->requester = $requester;
    }

    public function getFacture(): Facture
    {
        return $this->facture;
    }

    public function getRequester(): User
    {
        return $this->requester;
    }
}