<?php

declare(strict_types=1);

namespace App\Repositories\Transaction;

use Core\Application\Contracts\DBTransactionInterface;
use Illuminate\Support\Facades\DB;

final class DBTransaction implements DBTransactionInterface
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit()
    {
        DB::commit();
    }

    public function rollback()
    {
        DB::rollBack();
    }
}
