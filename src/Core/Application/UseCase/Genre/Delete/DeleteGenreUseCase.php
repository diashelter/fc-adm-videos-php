<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Delete;

use Core\Domain\Repository\GenreRepositoryInterface;

final class DeleteGenreUseCase
{
    public function __construct(
        private GenreRepositoryInterface $repository,
    ) {}

    public function execute(InputDeleteGenreDto $input): OutputDeleteGenreDto
    {
        $response = $this->repository->delete($input->id);

        return new OutputDeleteGenreDto($response);
    }
}
