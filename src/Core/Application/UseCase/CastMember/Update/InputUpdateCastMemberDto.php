<?php

namespace Core\Application\UseCase\CastMember\Update;

class InputUpdateCastMemberDto
{
    public function __construct(
        public string $id,
        public string $name,
        public string $type
    )
    {
    }
}
