<?php

namespace Core\Application\UseCase\CastMember\Create;

use Core\Domain\Entity\CastMember;
use Core\Domain\Repository\CastMemberRepositoryInterface;

class CreateCastMemberUseCase
{
    public function __construct(
        private readonly CastMemberRepositoryInterface $castMemberRepository,
    ) {
    }

    public function execute(CreateCastMemberInput $input): CreateCastMemberOutput
    {
        $castMember = CastMember::create($input->name, $input->type);
        $this->castMemberRepository->insert($castMember);
        return new CreateCastMemberOutput(
            id: $castMember->id(),
            name: $castMember->name,
            type: $castMember->type->value,
            createdAt: $castMember->createdAt(),
        );
    }
}
