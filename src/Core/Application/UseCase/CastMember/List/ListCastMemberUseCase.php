<?php

declare(strict_types=1);

namespace Core\Application\UseCase\CastMember\List;

use Core\Domain\Repository\CastMemberRepositoryInterface;

class ListCastMemberUseCase
{
    public function __construct(
        private CastMemberRepositoryInterface $castMemberRepository
    ) {}

    public function execute(string $castMemberId): OutputListCastMember
    {
        $castMember = $this->castMemberRepository->findById($castMemberId);

        return new OutputListCastMember(
            id: $castMember->id(),
            name: $castMember->name,
            type: (string) $castMember->type->value,
            created_at: $castMember->createdAt(),
        );
    }
}
