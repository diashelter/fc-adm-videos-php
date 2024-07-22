<?php

namespace Core\Application\UseCase\CastMember\ListAll;

class ListCastMembersResponse
{
    public function __construct(
        public array $items,
        public int   $total,
        public int   $currentPage,
        public int   $lastPage,
        public int   $firstPage,
        public int   $perPage,
        public int   $to,
        public int   $from,
    )
    {
    }
}
