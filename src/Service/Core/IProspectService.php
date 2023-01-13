<?php

namespace App\Service\Core;

use App\Entity\User;

interface IProspectService
{
    public function getAll(): array;
    public function getByCommercial(string $commercialId): array;
    public function create(string $commercialId, array $raw): User;
    public function read(string $id): User;
    public function update(string $id, array $raw): User;
}