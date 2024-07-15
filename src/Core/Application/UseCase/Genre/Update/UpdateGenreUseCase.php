<?php

declare(strict_types=1);

namespace Core\Application\UseCase\Genre\Update;

use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Application\Contracts\DBTransactionInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;

final class UpdateGenreUseCase
{
    public function __construct(
        private GenreRepositoryInterface $repository,
        private DBTransactionInterface $transaction,
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function execute(InputUpdateGenreDto $input): OutputUpdateGenreDto
    {
        $genre = $this->repository->findById($input->id);

        try {
            $genre->update(
                name: $input->name,
            );

            foreach ($input->categoriesId as $categoryId) {
                $genre->addCategory($categoryId);
            }

            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->update($genre);

            $this->transaction->commit();

            return new OutputUpdateGenreDto(
                id: (string)$genreDb->id(),
                name: $genreDb->name,
                is_active: $genreDb->isActive(),
                created_at: $genreDb->createdAt(),
            );
        } catch (\Throwable $th) {
            $this->transaction->rollback();
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
