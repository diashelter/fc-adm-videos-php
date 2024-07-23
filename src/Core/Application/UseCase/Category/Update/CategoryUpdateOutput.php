<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Category\Update;

final class CategoryUpdateOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $description = '',
        public bool $is_active = true,
        public string $created_at = '',
    ) {}
}
