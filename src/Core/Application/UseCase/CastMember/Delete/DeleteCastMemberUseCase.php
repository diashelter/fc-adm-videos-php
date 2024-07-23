<?php

namespace Core\Application\UseCase\CastMember\Delete;

use Core\Domain\Repository\CastMemberRepositoryInterface;

class DeleteCastMemberUseCase
{
    public function __construct(private CastMemberRepositoryInterface $repository) {}

    public function execute(string $id): DeleteCastMemberOutput
    {
        $response = $this->repository->delete($id);

        return new DeleteCastMemberOutput($response);
    }
}
