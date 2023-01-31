<?php

namespace App\EventSubscriber;

use App\Entity\History;
use App\Event\Facture\CreateFactureEvent;
use App\Event\Facture\UpdateFactureEvent;
use App\Event\Facture\ValidateFactureEvent;
use App\Event\Prospect\UpdateProspectEvent;
use App\Repository\HistoryRepository;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FactureListener implements EventSubscriberInterface
{
    private HistoryRepository $historyRepository;
    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    public function onFactureCreate(CreateFactureEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getFacture()->getId()))
            ->setTarget($event->getFacture()->jsonSerialize())
            ->setSource($event->getRequester())
            ->setHistoryType("FACTURE_CREATE")
            ->setMessage("création de la facture");

        $this->historyRepository->save($trace, true);
    }

    public function onFactureUpdate(UpdateFactureEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getFacture()->getId()))
            ->setTarget($event->getFacture()->jsonSerialize())
            ->setSource($event->getRequester())
            ->setHistoryType("FACTURE_UPDATE")
            ->setMessage("modification de la facture");

        $this->historyRepository->save($trace, true);
    }

    public function onFactureValidate(ValidateFactureEvent $event): void {
        $trace = new History();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTargetId(Uuid::fromString($event->getFacture()->getId()))
            ->setTarget($event->getFacture()->jsonSerialize())
            ->setSource($event->getRequester())
            ->setHistoryType("FACTURE_VALIDATE")
            ->setMessage("validation de la facture");

        $this->historyRepository->save($trace, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CreateFactureEvent::NAME => 'onFactureCreate',
            UpdateFactureEvent::NAME => 'onFactureUpdate',
            ValidateFactureEvent::NAME => 'onFactureValidate'
        ];
    }
}