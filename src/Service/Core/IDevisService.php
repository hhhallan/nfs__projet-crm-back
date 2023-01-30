<?php

namespace App\Service\Core;

use App\Entity\Devis;

interface IDevisService
{
    public function getAll(): array;
    public function getByCommercial(string $commercialId): array;
    public function getByClient(string $clientId): array;

    public function read(string $id): Devis;
    public function create(array $raw): Devis;
    public function update(string $id, array $raw): Devis;
}