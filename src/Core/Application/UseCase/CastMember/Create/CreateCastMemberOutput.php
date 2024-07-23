<?php

declare(strict_types=1);

namespace Core\Application\UseCase\CastMember\Create;

class CreateCastMemberOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public string $type,
        public string $createdAt,
    ) {}
}
