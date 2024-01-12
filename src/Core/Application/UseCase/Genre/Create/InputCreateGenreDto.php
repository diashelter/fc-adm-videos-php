<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Create;

final class InputCreateGenreDto
{
    public function __construct(
        public string $name,
        public array $categoriesId = [],
        public bool $isActive = true,
    ) {
    }
}
