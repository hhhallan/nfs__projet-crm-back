<?php

namespace App\EventSubscriber;

use App\Entity\Historic;
use App\Event\CreateDevisEvent;
use App\Event\UpdateDevisEvent;
use App\Repository\HistoryRepository;
use DateTimeImmutable;
use JetBrains\PhpStorm\ArrayShape;
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
        $trace = new Historic();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTarget($event->getDevis()->jsonSerialize())
            ->setSource($event->getRequester())
            ->setHistoricType("DEVIS_CREATE")
            ->setMessage("création du devis");

        $this->historyRepository->save($trace, true);
    }

    public function onDevisUpdate(UpdateDevisEvent $event): void {
        $trace = new Historic();
        $trace->setDate(new DateTimeImmutable("now"))
            ->setTarget($event->getDevis()->jsonSerialize())
            ->setSource($event->getRequester())
            ->setHistoricType("DEVIS_UPDATE")
            ->setMessage("modification du devis");

        $this->historyRepository->save($trace, true);
    }

    #[ArrayShape([CreateDevisEvent::NAME => "string", UpdateDevisEvent::NAME => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            CreateDevisEvent::NAME => 'onDevisCreate',
            UpdateDevisEvent::NAME => 'onDevisUpdate'
        ];
    }
}