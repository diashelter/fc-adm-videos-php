<?php
declare(strict_types=1);

namespace Core\Application\DTO\Category;

final class CreateCategoryInput
{
    public function __construct(
        public string $name,
        public string $description = '',
        public bool $isActive = true,
    )
    {
        
    }
}
