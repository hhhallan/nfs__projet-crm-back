<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\HistoryRepository;
use App\Service\Core\IHistoryService;

class HistoryService implements IHistoryService
{
    private readonly HistoryRepository $historyRepository;
    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    public function getAll(): array
    {
        return $this->historyRepository->findAll();
    }

    public function getByTargetId(string $id): array
    {
        return $this->historyRepository->findBy(['TargetId' => $id]);
    }

    public function getBySource(User $user): array
    {
        return $user->getHistories()->toArray();
    }
}