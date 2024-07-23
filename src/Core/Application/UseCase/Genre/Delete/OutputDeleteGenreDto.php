<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Delete;

final class OutputDeleteGenreDto
{
    public function __construct(
        public bool $success,
    ) {}
}
