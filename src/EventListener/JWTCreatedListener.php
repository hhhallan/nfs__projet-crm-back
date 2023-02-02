<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        /** @var User $user */
        $user = $event->getUser();
        $payload['id'] = $user->getId();

        $event->setData($payload);
    }
}