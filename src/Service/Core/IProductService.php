<?php

namespace App\Service\Core;

use App\Entity\Product;

interface IProductService
{
    public function getAll(): array;
    public function getArchived(): array;

    public function create(array $raw): Product;
    public function read(string $id): Product;
    public function update(string $id, array $raw): Product;
    public function delete(string $id): Product;
}