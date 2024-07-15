<?php

namespace Core\Application\UseCase\CastMember\Create;

class CreateCastMemberInput
{
    public function __construct(
        public string $name,
        public string $type,
    ) {
    }
}
