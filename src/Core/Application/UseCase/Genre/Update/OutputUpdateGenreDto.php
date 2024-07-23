<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Update;

final class OutputUpdateGenreDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active,
        public string $created_at,
    ) {}
}
