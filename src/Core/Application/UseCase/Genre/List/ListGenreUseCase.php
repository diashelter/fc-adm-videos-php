<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Genre\List;

use Core\Domain\Repository\GenreRepositoryInterface;

final class ListGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $genreRepository,
    ){}

    public function execute(InputListGenreDto $input): OutputListGenreDto
    {
        $genre = $this->genreRepository->findById($input->id);
        return new OutputListGenreDto(
            id: $genre->id(),
            name: $genre->name,
            is_active: $genre->isActive(),
            created_at: $genre->createdAt()
        );
    }
}
