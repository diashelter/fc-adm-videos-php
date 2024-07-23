<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\List;

final class OutputListGenreDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active,
        public string $created_at,
    ) {}
}
