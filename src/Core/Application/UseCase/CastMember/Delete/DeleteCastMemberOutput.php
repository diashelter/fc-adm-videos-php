<?php

namespace Core\Application\UseCase\CastMember\Delete;

class DeleteCastMemberOutput
{
    public function __construct(
        public bool $success,
    ) {}
}
