<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;
use Core\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    public function __construct(
        private Model $model = new \App\Models\Genre(),
    ) {
    }

    public function insert(Genre $genre): Genre
    {
        $genreModel = $this->model->create([
            'id' => $genre->id,
            'name' => $genre->name,
            'is_active' => $genre->isActive(),
            'created_at' => $genre->createdAt,
        ]);

        if (count($genre->categoriesId) > 0) {
            $sqlValues = "";
            foreach ($genre->categoriesId as $item) {
                if ($sqlValues !== "") {
                    $sqlValues .= ", ";
                }
                $sqlValues .= "('{$item}', '{$genreModel->id}')";
            }
            DB::insert("INSERT INTO `category_genre` (`category_id`, `genre_id`) VALUES {$sqlValues}");
        }
        return $this->toGenre($genreModel);
    }

    public function findById(string $id): Genre
    {
        if (!$genreModel = $this->model->find($id)) {
            throw new NotFoundException("genre not found");
        }
        return $this->toGenre($genreModel);
    }

    public function findAll(string $filter = '', string $order = 'DESC'): array
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->get();

        return $result->toArray();
    }

    public function paginate(string $filter = '', string $order = 'DESC', int $page = 1, int $totalPage = 15): PaginateInterface
    {
        $result = $this->model
            ->where(function ($query) use ($filter) {
                if ($filter) {
                    $query->where('name', 'LIKE', "%{$filter}%");
                }
            })
            ->orderBy('name', $order)
            ->paginate($totalPage);

        return new PaginationPresenter($result);
    }

    public function update(Genre $genre): Genre
    {
        if (!$genreDb = $this->model->find($genre->id)) {
            throw new NotFoundException("Genre {$genre->id} not found");
        }

        $genreDb->update(['name' => $genre->name]);

        if (count($genre->categoriesId) > 0) {
            $sqlValues = "";
            foreach ($genre->categoriesId as $item) {
                if ($sqlValues !== "") {
                    $sqlValues .= ", ";
                }
                $sqlValues .= "('{$item}', '{$genre->id}')";
            }
            DB::delete("DELETE FROM `category_genre` WHERE genre_id = ?", [$genre->id]);
            DB::insert("INSERT INTO `category_genre` (`category_id`, `genre_id`) VALUES {$sqlValues}");
        }

        return $this->toGenre($genreDb->refresh());
    }

    public function delete(string $id): bool
    {
        if (!$genreDb = $this->model->find($id)) {
            throw new NotFoundException("genre not found");
        }
        return $genreDb->delete();
    }

    private function toGenre(object $object): Genre
    {
        $entity = new Genre(
            id: new Uuid($object->id),
            name: $object->name,
            createdAt: new DateTimeImmutable($object->created_at),
        );
        ((bool) $object->is_active) ? $entity->activate() : $entity->deactivate();

        return $entity;
    }
}
