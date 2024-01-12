<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Genre\ListAll;

use Core\Domain\Repository\GenreRepositoryInterface;

final class ListGenresUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $genreRepository
    ) {
    }

    public function execute(InputListGenresDto $input): OutputListGenresDto
    {
        $response = $this->genreRepository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );
        return new OutputListGenresDto(
            items: $response->items(),
            total: $response->total(),
            currentPage: $response->currentPage(),
            lastPage: $response->lastPage(),
            firstPage: $response->firstPage(),
            perPage: $response->perPage(),
            to: $response->to(),
            from: $response->from(),
        );
    }
}
