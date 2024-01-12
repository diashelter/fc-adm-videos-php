<?php
declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\CategoryModel;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginateInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;

final class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    public function __construct(
        protected CategoryModel $model
    ) {
    }

    public function insert(Category $category): Category
    {
        $categoryModel = $this->model->create([
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive(),
            'created_at' => $category->createdAt,
        ]);
        return $this->toCategory($categoryModel);
    }

    public function findById(string $id): Category
    {
        $categoryModel = $this->model->find($id);
        if (!$categoryModel) {
            throw new NotFoundException("category not found");
        }
        return $this->toCategory($categoryModel);
    }

    public function getIdsListIds(array $categoriesId = []): array
    {
        return $this->model->whereIn('id', $categoriesId)->pluck('id')->toArray();
    }

    public function findAll(string $filter = '', string $order = 'DESC'): array
    {
        $listCategories = $this->model
        ->where(function ($query) use ($filter) {
            if ($filter) {
                $query->where('name', 'LIKE', "%{$filter}%");
            }
        })
        ->orderBy('id', $order)
        ->get();
        return $listCategories->toArray();
    }

    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15): PaginateInterface
    {
        $query = $this->model;
        if ($filter) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
    }

    public function update(Category $category): Category
    {
        $categoryDb = $this->model->find($category->id);
        if (!$categoryDb) {
            throw new NotFoundException("category not found");
        }
        $categoryDb->update([
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive(),
        ]);
        return $this->toCategory($categoryDb->refresh());
    }

    public function delete(string $id): bool
    {
        $categoryDb = $this->model->find($id);
        if (!$categoryDb) {
            throw new NotFoundException("category not found");
        }
        return $categoryDb->delete();
    }

    private function toCategory(object $object): Category
    {
        $entity = new Category(
            id: $object->id,
            name: $object->name,
            description: $object->description,
        );
        ((bool) $object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
