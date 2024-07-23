<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Category\ListAll;

final class ListCategoriesOutput
{
    public function __construct(
        public array $items,
        public int $total,
        public int $current_page,
        public int $last_page,
        public int $first_page,
        public int $per_page,
        public int $to,
        public int $from,
    ) {}
}
