<?php

namespace App\EventListener;

use App\Entity\History;
use App\Event\Devis\CreateDevisEvent;
use App\Event\Devis\UpdateDevisEvent;
use App\Event\Prospect\UpdateProspectEvent;
use App\Repository\HistoryRepository;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DevisListener implements EventSubscriberInterface
{
    private HistoryRepository $historyRepository;
    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    public function onDevisCreate(CreateDevisEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getDevis()->getId()))
            ->setTarget($event->getDevis()->jsonSerialize())
            ->setSource($event->getRequester())
            ->setHistoryType("DEVIS_CREATE")
            ->setMessage("création du devis");

        $this->historyRepository->save($trace, true);
    }

    public function onDevisUpdate(UpdateDevisEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getDevis()->getId()))
            ->setTarget($event->getDevis()->jsonSerialize())
            ->setSource($event->getRequester())
            ->setHistoryType("DEVIS_UPDATE")
            ->setMessage("modification du devis");

        $this->historyRepository->save($trace, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateDevisEvent::NAME => 'onDevisCreate',
            UpdateDevisEvent::NAME => 'onDevisUpdate'
        ];
    }
}