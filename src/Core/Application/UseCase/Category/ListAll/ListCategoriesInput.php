<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Category\ListAll;

final class ListCategoriesInput
{
    public function __construct(
        public string $filter = '',
        public string $order = 'DESC',
        public int $page = 1,
        public int $totalPage = 15,
    )
    {

    }
}
