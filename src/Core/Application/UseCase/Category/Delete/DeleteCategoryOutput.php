<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Category\Delete;

class DeleteCategoryOutput
{
    public function __construct(
        public bool $success
    ) {
    }
}
