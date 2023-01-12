<?php

namespace App\Service\Core;

interface IClientService
{
    public function getAll(): array;
    public function getByCommercial(string $commercialId): array;
}