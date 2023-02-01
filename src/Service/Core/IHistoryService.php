<?php

namespace App\Service\Core;

use App\Entity\User;

interface IHistoryService
{
    public function getAll(): array;
    public function getByTargetId(string $id): array;
    public function getBySource(User $user): array;
}