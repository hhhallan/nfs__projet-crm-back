<?php

namespace App\Service\Core;

use App\Entity\User;

interface IUserService
{
    public function getAll(): array;
    public function getById(string $id): User;
}