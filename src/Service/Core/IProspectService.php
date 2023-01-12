<?php

namespace App\Service\Core;

interface IProspectService
{
    public function getAll(): array;
    public function getByCommercial(string $commercialId): array;
}