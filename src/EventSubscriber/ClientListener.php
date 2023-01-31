<?php

namespace App\EventSubscriber;

use App\Entity\History;
use App\Event\Client\CreateClientEvent;
use App\Event\Client\UpdateClientEvent;
use App\Repository\HistoryRepository;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClientListener implements EventSubscriberInterface
{
    private HistoryRepository $historyRepository;
    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    public function onClientCreate(CreateClientEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getClient()->getId()))
            ->setTarget($event->getClient()->jsonSerializeEmpty())
            ->setSource($event->getRequester())
            ->setHistoryType("CLIENT_CREATE")
            ->setMessage("convertion du prospect en client");

        $this->historyRepository->save($trace, true);
    }

    public function onClientUpdate(UpdateClientEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getClient()->getId()))
            ->setTarget($event->getClient()->jsonSerializeEmpty())
            ->setSource($event->getRequester())
            ->setHistoryType("CLIENT_UPDATE")
            ->setMessage("modification du client");

        $this->historyRepository->save($trace, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateClientEvent::NAME => 'onClientCreate',
            UpdateClientEvent::NAME => 'onClientUpdate',
        ];
    }
}