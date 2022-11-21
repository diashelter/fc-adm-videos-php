<?php
declare(strict_types=1);

namespace Core\Application\DTO\Category;

class DeleteCategoryOutput
{
    public function __construct(
        public bool $success
    ) {
    }
}
