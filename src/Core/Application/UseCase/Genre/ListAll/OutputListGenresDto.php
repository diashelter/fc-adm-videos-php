<?php
declare(strict_types=1);

namespace Core\Application\UseCase\Genre\ListAll;

final class OutputListGenresDto
{
    public function __construct(
        public array $items,
        public int $total,
        public int $currentPage,
        public int $lastPage,
        public int $firstPage,
        public int $perPage,
        public int $to,
        public int $from,
    ) {
    }
}
