<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Create;

use Core\Application\Contracts\DBTransactionInterface;
use Core\Application\UseCase\Genre\Create\InputCreateGenreDto;
use Core\Application\UseCase\Genre\Create\OutputCreateGenreDto;
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;

final class CreateGenreUseCase
{
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private DBTransactionInterface $tansaction,
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function execute(InputCreateGenreDto $input): OutputCreateGenreDto
    {
        try {
            $genre = new Genre(
                name: $input->name,
                isActive: $input->isActive,
                categoriesId: $input->categoriesId,
            );

            $this->validateCategoriesId($input->categoriesId);

            $genreEntity = $this->genreRepository->insert($genre);

            $this->tansaction->commit();

            return new OutputCreateGenreDto(
                id: $genreEntity->id(),
                name: $genreEntity->name,
                is_active: $genreEntity->isActive(),
                created_at: $genreEntity->createdAt(),
            );
        } catch (\Throwable $th) {
            $this->tansaction->rollback();
            throw $th;
        }
    }

    private function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );
            throw new NotFoundException($msg);
        }
    }
}
