<?php

declare(strict_types=1);

namespace Core\Domain\Repository;

use Core\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function insert(Category $category): Category;

    public function findById(string $id): Category;

    public function getIdsListIds(array $categoriesId = []): array;

    public function findAll(string $filter = '', string $order = 'DESC'): array;

    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15): PaginateInterface;

    public function update(Category $category): Category;

    public function delete(string $id): bool;
}
