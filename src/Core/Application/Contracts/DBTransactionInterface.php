<?php

declare(strict_types=1);

namespace Core\Application\Contracts;

interface DBTransactionInterface
{
    public function commit();
    public function rollback();
}
