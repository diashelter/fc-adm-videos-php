<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\List;

final class InputListGenreDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
