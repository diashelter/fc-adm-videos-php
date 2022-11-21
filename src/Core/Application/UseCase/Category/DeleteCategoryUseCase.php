<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Category;

use Core\Application\DTO\Category\CategoryInput;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Application\DTO\Category\DeleteCategoryOutput;

final class DeleteCategoryUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    )
    {

    }

    public function execute(CategoryInput $input): DeleteCategoryOutput
    {
        $categoryResponse = $this->categoryRepository->delete($input->id);
        return new DeleteCategoryOutput(
            success: $categoryResponse,
        );
    }
}
