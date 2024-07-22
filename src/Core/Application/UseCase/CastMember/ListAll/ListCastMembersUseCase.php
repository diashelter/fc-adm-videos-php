<?php

namespace Core\Application\UseCase\CastMember\ListAll;

use Core\Domain\Repository\CastMemberRepositoryInterface;

class ListCastMembersUseCase
{
    public function __construct(
        private CastMemberRepositoryInterface $repository
    )
    {
    }

    public function execute(InputCastMembers $input): ListCastMembersResponse
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage,
        );
        return new ListCastMembersResponse(
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
