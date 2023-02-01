<?php

namespace App\Event\Prospect;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateProspectEvent extends Event
{
    public const NAME = 'prospect.update';
    private User $prospect;
    private User $requester;

    public function __construct(User $prospect, User $requester)
    {
        $this->prospect = $prospect;
        $this->requester = $requester;
    }

    public function getProspect(): User
    {
        return $this->prospect;
    }

    public function getRequester(): User
    {
        return $this->requester;
    }
}