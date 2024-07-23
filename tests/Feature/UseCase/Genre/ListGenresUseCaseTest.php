<?php

declare(strict_types=1);

use App\Models\Genre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Application\UseCase\Genre\ListAll\InputListGenresDto;
use Core\Application\UseCase\Genre\ListAll\ListGenresUseCase;

it('should list genres empty use case', function () {
    $useCase = new ListGenresUseCase(new GenreEloquentRepository());
    $response = $useCase->execute(new InputListGenresDto());
    $this->assertCount(0, $response->items);
});

it('should list genres use case', function () {
    $genressDb = Genre::factory(20)->create();
    $useCase = new ListGenresUseCase(new GenreEloquentRepository());
    $response = $useCase->execute(new InputListGenresDto());
    $this->assertCount(15, $response->items);
    $this->assertEquals(count($genressDb), $response->total);
});
