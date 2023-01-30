<?php

namespace App\Service\Core;

use App\Entity\Facture;

interface IFactureService
{
    public function getAll(): array;
    public function getByClient(string $id): array;
    public function getByCommercial(string $id): array;

    public function create(string $devisId): Facture;
    public function read(string $id): Facture;
    public function update(string $id, array $raw): Facture;
    public function changeState(string $id, string $state): Facture;
}