<?php

declare(strict_types=1);

use App\Models\CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use Core\Application\UseCase\Genre\Create\CreateGenreUseCase;
use Core\Application\UseCase\Genre\Create\InputCreateGenreDto;
use Core\Domain\Exception\NotFoundException;

it('should create genre use case', function () {
    $useCase = new CreateGenreUseCase(new GenreEloquentRepository(), new DBTransaction(), new CategoryEloquentRepository(new CategoryModel()));
    $genreCreated = $useCase->execute(new InputCreateGenreDto(name: 'Test'));
    expect('Test')->toBe($genreCreated->name);
    $this->assertNotEmpty($genreCreated->id);
    $this->assertDatabaseHas('genres', [
        'id' => $genreCreated->id,
        'name' => $genreCreated->name,
    ]);
});

it('should throw exceptioin the categories invalid', function () {
    expect(function () {
        $categories = CategoryModel::factory(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        $useCase = new CreateGenreUseCase(new GenreEloquentRepository(), new DBTransaction(), new CategoryEloquentRepository(new CategoryModel()));
        array_push($categoriesIds, 'fake_id');
        $genreCreated = $useCase->execute(new InputCreateGenreDto(name: 'Test', categoriesId: $categoriesIds));
    })->toThrow(NotFoundException::class);
});
