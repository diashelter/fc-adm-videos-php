<?php

declare(strict_types=1);

namespace Core\Application\UseCase\CastMember\List;

use App\Repositories\Eloquent\CastMemberEloquentRepository;

class ListCastMemberUseCase
{
    public function __construct(
        private readonly CastMemberEloquentRepository $castMemberRepository
    )
    {
    }

    public function execute(string $castMemberId): OutputListCastMember
    {
        $castMember = $this->castMemberRepository->findById($castMemberId);
        return new OutputListCastMember(
            id: $castMember->id(),
            name: $castMember->name,
            type: (string)$castMember->type->value,
            created_at: $castMember->createdAt(),
        );
    }
}