<?php
namespace App\Repositories;

interface RepositoryInterface
{
    public function all(): array;
    public function find(int $id): ?object;
    public function save(object $entity): void;
}
