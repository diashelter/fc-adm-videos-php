<?php

declare(strict_types=1);

namespace Core\Domain\Enum;

enum CastMemberType: int
{
    case DIRECTOR = 1;
    case ACTOR = 2;
}
