<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Category\Create;

use Core\Application\DTO\Category\CategoryOutput;
use Core\Application\DTO\Category\CreateCategoryInput;
use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;

final class CreateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function execute(CreateCategoryInput $input): CategoryOutput
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive
        );
        $categoryEntity = $this->categoryRepository->insert($category);
        return new CategoryOutput(
            id: $categoryEntity->id(),
            name: $categoryEntity->name,
            description: $categoryEntity->description,
            is_active: $categoryEntity->isActive(),
            created_at: $categoryEntity->createdAt(),
        );
    }
}
