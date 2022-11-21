<?php
declare(strict_types=1);

namespace Core\Application\DTO\Category;

final class CategoryUpdateInput
{
    public function __construct(
        public string $id,
        public string $name,
        public string|null $description = null,
        public bool $isActive = true,
    )
    {
        
    }
}