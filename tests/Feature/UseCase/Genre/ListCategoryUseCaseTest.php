<?php

declare(strict_types=1);

use App\Models\Genre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Application\UseCase\Genre\List\InputListGenreDto;
use Core\Application\UseCase\Genre\List\ListGenreUseCase;

it('should list genre use case', function () {
    $useCase = new ListGenreUseCase(new GenreEloquentRepository());
    $genre = Genre::factory()->create();

    $responseUseCase = $useCase->execute(new InputListGenreDto(id: $genre->id));

    $this->assertEquals($genre->id, $responseUseCase->id);
    $this->assertEquals($genre->name, $responseUseCase->name);
});
