<?php

namespace App\Event\Client;

use App\Entity\Devis;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CreateClientEvent extends Event
{
    public const NAME = 'client.create';
    private User $client;
    private User $requester;

    public function __construct(User $client , User $requester)
    {
        $this->client = $client;
        $this->requester = $requester;
    }

    public function getClient(): User
    {
        return $this->client;
    }

    public function getRequester(): User
    {
        return $this->requester;
    }
}