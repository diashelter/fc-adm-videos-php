<?php
declare(strict_types=1);

namespace Core\Application\DTO\Category;

final class CategoryInput
{
    public function __construct(
        public string $id = '',
    ) {
    }
}
