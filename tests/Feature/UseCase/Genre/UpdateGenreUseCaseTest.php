<?php

declare(strict_types=1);

use App\Models\CategoryModel;
use App\Models\Genre;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Application\UseCase\Genre\Update\InputUpdateGenreDto;
use Core\Application\UseCase\Genre\Update\UpdateGenreUseCase;
use Core\Domain\Exception\NotFoundException;

it('should update genre use case', function () {
    $repository = new GenreEloquentRepository();
    $repositoryCategory = new CategoryEloquentRepository(new CategoryModel());

    $useCase = new UpdateGenreUseCase($repository, new DBTransaction(), $repositoryCategory);

    $genre = Genre::factory()->create();

    $categories = CategoryModel::factory()->count(10)->create();
    $categoriesIds = $categories->pluck('id')->toArray();

    $useCase->execute(new InputUpdateGenreDto(id: $genre->id, name: 'New Name', categoriesId: $categoriesIds));

    $this->assertDatabaseHas('genres', [
        'name' => 'New Name',
    ]);

    $this->assertDatabaseCount('category_genre', 10);
});

it('should throw exceptioin the update genre with categories invalid', function () {
    expect(function () {
        $genre = Genre::factory()->create();
        $categories = CategoryModel::factory(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        $useCase = new UpdateGenreUseCase(new GenreEloquentRepository(), new DBTransaction(), new CategoryEloquentRepository(new CategoryModel()));
        array_push($categoriesIds, 'fake_id');
        $genreCreated = $useCase->execute(new InputUpdateGenreDto(id: $genre->id, name: 'Test', categoriesId: $categoriesIds));
    })->toThrow(NotFoundException::class);
});
