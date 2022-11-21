<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Category;

use Core\Application\DTO\Category\CategoryUpdateInput;
use Core\Application\DTO\Category\CategoryUpdateOutput;
use Core\Domain\Repository\CategoryRepositoryInterface;

final class UpdateCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    )
    {
    }

    public function execute(CategoryUpdateInput $input): CategoryUpdateOutput
    {
        $category = $this->categoryRepository->findById($input->id);
        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description
        );
        $categoryUpdated = $this->categoryRepository->update($category);
        return new CategoryUpdateOutput(
            id: $categoryUpdated->id(),
            name: $categoryUpdated->name,
            description: $categoryUpdated->description,
            is_active: $categoryUpdated->isActive(),
            created_at: $categoryUpdated->createdAt(),
        );
    }
}
