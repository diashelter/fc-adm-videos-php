<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Category\List;

use Core\Application\UseCase\Category\CategoryInput;
use Core\Application\UseCase\Category\CategoryOutput;
use Core\Domain\Repository\CategoryRepositoryInterface;

final class ListCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function execute(CategoryInput $input): CategoryOutput
    {
        $category = $this->categoryRepository->findById($input->id);
        return new CategoryOutput($category->id(), $category->name, $category->description, $category->isActive(), $category->createdAt());
    }
}
