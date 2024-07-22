<?php

namespace Core\Application\UseCase\CastMember\Update;

class CastMemberOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $type,
        public string $createdAt
    )
    {
    }
}
