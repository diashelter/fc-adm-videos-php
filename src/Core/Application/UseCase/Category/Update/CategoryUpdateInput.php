<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Category\Update;

final class CategoryUpdateInput
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = null,
        public bool $isActive = true,
    ) {}
}
