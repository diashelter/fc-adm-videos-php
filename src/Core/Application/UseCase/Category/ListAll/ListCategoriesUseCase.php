<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Category\ListAll;

use Core\Application\DTO\Category\CategoryInput;
use Core\Application\DTO\Category\CategoryOutput;
use Core\Application\DTO\Category\ListCategoriesInput;
use Core\Application\DTO\Category\ListCategoriesOutput;
use Core\Domain\Repository\CategoryRepositoryInterface;

final class ListCategoriesUseCase
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function execute(ListCategoriesInput $input): ListCategoriesOutput
    {
        $categories = $this->categoryRepository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );
        return new ListCategoriesOutput(
            items: $categories->items(),
            total: $categories->total(),
            current_page: $categories->currentPage(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from(),
        );
    }
}
