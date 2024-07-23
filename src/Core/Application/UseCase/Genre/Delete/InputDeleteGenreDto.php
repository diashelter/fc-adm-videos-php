<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Delete;

final class InputDeleteGenreDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
