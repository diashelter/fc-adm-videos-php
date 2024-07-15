<?php

namespace Core\Application\UseCase\CastMember\List;

class OutputListCastMember
{

    public function __construct(
        public string $id,
        public string $name,
        public string $type,
        public string $created_at
    )
    {
    }
}
