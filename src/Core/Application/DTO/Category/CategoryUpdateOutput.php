<?php
declare(strict_types=1);

namespace Core\Application\DTO\Category;

final class CategoryUpdateOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public string|null $description = '',
        public bool $is_active = true,
        public string $created_at = '',
    )
    {

    }
}
