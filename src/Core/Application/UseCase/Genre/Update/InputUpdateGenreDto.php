<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Update;

final class InputUpdateGenreDto
{
    public function __construct(
        public string $id,
        public string $name,
        public array $categoriesId = [],
        public bool $isActive = true,
    ) {
    }
}
