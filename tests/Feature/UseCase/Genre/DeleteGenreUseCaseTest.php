<?php
declare(strict_types=1);

use App\Models\Genre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Application\UseCase\Genre\Delete\DeleteGenreUseCase;
use Core\Application\UseCase\Genre\Delete\InputDeleteGenreDto;

it('should delete genre use case', function () {
    $genreDb = Genre::factory()->create();
    $useCase = new DeleteGenreUseCase(new GenreEloquentRepository());
    $response = $useCase->execute(new InputDeleteGenreDto(id: $genreDb->id));
    $this->assertTrue($response->success);
    $this->assertSoftDeleted($genreDb);
});
