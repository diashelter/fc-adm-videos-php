<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\CastMember;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;
use Illuminate\Database\Eloquent\Model;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    public function __construct(
        private Model $model = new \App\Models\CastMember(),
    ) {}

    public function insert(CastMember $castMember): CastMember
    {
        $dataDb = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt(),
        ]);

        return $this->convertToEntity($dataDb);
    }

    public function findById(string $id): CastMember
    {
        if (! $dataDb = $this->model->find($id)) {
            throw new NotFoundException("Cast Member {$id} Not Found");
        }

        return $this->convertToEntity($dataDb);
    }

    public function findAll(string $filter = '', string $order = 'DESC'): array
    {
        $dataDb = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return $dataDb->toArray();
    }

    public function update(CastMember $castMember): CastMember
    {
        if (! $dataDb = $this->model->find($castMember->id())) {
            throw new NotFoundException("Cast Member {$castMember->id()} Not Found");
        }

        $dataDb->update([
            'name' => $castMember->name,
            'type' => $castMember->type->value,
        ]);

        $dataDb->refresh();

        return $this->convertToEntity($dataDb);
    }

    public function delete(string $id): bool
    {
        if (! $dataDb = $this->model->find($id)) {
            throw new NotFoundException("Cast Member {$id} Not Found");
        }

        return $dataDb->delete();
    }

    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15): PaginateInterface
    {
        $query = $this->model;
        if ($filter) {
            $query = $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query = $query->orderBy('name', $order);
        $dataDb = $query->paginate($totalPage);

        return new PaginationPresenter($dataDb);
    }

    private function convertToEntity(object $model): CastMember
    {
        return CastMember::restore(
            id: (string) $model->id,
            name: $model->name,
            type: (string) $model->type,
            createdAt: (string) $model->created_at
        );
    }
}
