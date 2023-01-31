<?php

namespace App\EventSubscriber;

use App\Entity\History;
use App\Event\Prospect\CreateProspectEvent;
use App\Event\Prospect\UpdateProspectEvent;
use App\Repository\HistoryRepository;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProspectListener implements EventSubscriberInterface
{
    private HistoryRepository $historyRepository;
    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    public function onProspectCreate(CreateProspectEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getProspect()->getId()))
            ->setTarget($event->getProspect()->jsonSerializeEmpty())
            ->setSource($event->getRequester())
            ->setHistoryType("PROSPECT_CREATE")
            ->setMessage("création du prospet");

        $this->historyRepository->save($trace, true);
    }

    public function onProspectUpdate(UpdateProspectEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getProspect()->getId()))
            ->setTarget($event->getProspect()->jsonSerializeEmpty())
            ->setSource($event->getRequester())
            ->setHistoryType("PROSPECT_UPDATE")
            ->setMessage("modification du prospet");

        $this->historyRepository->save($trace, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateProspectEvent::NAME => 'onProspectCreate',
            UpdateProspectEvent::NAME => 'onProspectUpdate',
        ];
    }
}