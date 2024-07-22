<?php

namespace Core\Application\UseCase\CastMember\Update;

use Core\Domain\Entity\CastMember;
use Core\Domain\Repository\CastMemberRepositoryInterface;

class UpdateCastMemberUseCase
{
    public function __construct(private CastMemberRepositoryInterface $repository)
    {
    }

    public function execute(InputUpdateCastMemberDto $input): CastMemberOutputDto
    {

        $entity = $this->repository->findById($input->id);
        $entity->update($input->name);
        $this->repository->update($entity);
        return new CastMemberOutputDto(
            id: $entity->id(),
            name: $entity->name,
            type: $entity->type->value,
            createdAt: $entity->createdAt(),
        );
    }
}
