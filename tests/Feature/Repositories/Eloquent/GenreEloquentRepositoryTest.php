<?php

declare(strict_types=1);

use App\Models\CategoryModel;
use App\Models\Genre as ModelsGenre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;
use Core\Domain\ValueObject\Uuid;

beforeEach(function () {
    $this->repository = new GenreEloquentRepository();
});

it('should insert genre', function () {
    $entity = new Genre(
        name: 'Test Genre',
    );
    $entityGenre = $this->repository->insert($entity);
    $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    expect($entityGenre)->toBeInstanceOf(Genre::class);
    $this->assertEquals($entity->name, $entityGenre->name);
    $this->assertEquals($entity->id, $entityGenre->id);
    $this->assertDatabaseHas('genres', [
        'id' => $entity->id,
        'name' => $entity->name,
    ]);
});

it('test Insert Genre Deactivate', function () {
    $entity = new Genre(name: 'New genre');
    $entity->deactivate();

    $this->repository->insert($entity);

    $this->assertDatabaseHas('genres', [
        'id' => $entity->id,
        'is_active' => false
    ]);
});

it('test Insert With Relationships', function () {
    $categories = CategoryModel::factory()->count(4)->create();
    $genre = new Genre(name: 'teste');
    foreach ($categories as $category) {
        $genre->addCategory($category->id);
    }

    $response = $this->repository->insert($genre);
    $this->assertDatabaseHas('genres', [
        'id' => $response->id(),
    ]);
    $this->assertDatabaseCount('category_genre', 4);
});

it('should return exception genre not found find by id', function () {
    expect(function () {
        $this->repository->findById('invalid_id');
    })->toThrow(NotFoundException::class, 'genre not found');
});

it('should find by id genre', function () {
    $genreModel = ModelsGenre::factory()->create();
    $genre = $this->repository->findById($genreModel->id);
    $this->assertInstanceOf(Genre::class, $genre);
    $this->assertEquals($genre->id, $genreModel->id);
    $this->assertEquals($genre->name, $genreModel->name);
    $this->assertEquals($genre->isActive, $genreModel->is_active);
});

it('should find all genres', function () {
    ModelsGenre::factory(10)->create();
    $listGenres = $this->repository->findAll();
    $this->assertCount(10, $listGenres);
});

it('test Find All With Filter', function () {
    ModelsGenre::factory()->count(10)->create([
        'name' => 'Teste'
    ]);
    ModelsGenre::factory()->count(10)->create();

    $genresDb = $this->repository->findAll(
        filter: 'Teste'
    );
    $this->assertEquals(10, count($genresDb));

    $genresDb = $this->repository->findAll();
    $this->assertEquals(20, count($genresDb));
});

it('should test pagination', function () {
    ModelsGenre::factory(20)->create();

    $response = $this->repository->paginate();

    $this->assertInstanceOf(PaginateInterface::class, $response);
    $this->assertCount(15, $response->items());
});

it('should test paginate without', function () {
    $response = $this->repository->paginate();

    $this->assertInstanceOf(PaginateInterface::class, $response);
    $this->assertCount(0, $response->items());
});

it('Update id NotFound', function () {
    $genreEntity = new Genre(name: 'test');
    expect(function () use ($genreEntity) {
        $this->repository->update($genreEntity);
    })->toThrow(NotFoundException::class, "Genre {$genreEntity->id} not found");
});

it('Update genre', function () {
    $modelsGenre = ModelsGenre::factory()->create();
    $genreEntity = new Genre(id: new Uuid($modelsGenre->id), name: 'test update');
    $genreUpdated = $this->repository->update($genreEntity);
    $this->assertInstanceOf(Genre::class, $genreUpdated);
    $this->assertNotEquals($genreUpdated->name, $modelsGenre->name);
    $this->assertEquals('test update', $genreUpdated->name);
    $this->assertDatabaseHas('genres', [
        "id" => $modelsGenre->id,
        "name" => "test update"
    ]);
});

it('test deleted id nor found genre', function () {
    expect(function () {
        $this->repository->delete('invalid_id');
    })->toThrow(NotfoundException::class, 'genre not found');
});

it('should delete genre', function () {
    $genreDb = ModelsGenre::factory()->create();
    $response = $this->repository->delete($genreDb->id);
    $this->assertSoftDeleted('genres', [
        'id' => $genreDb->id,
    ]);
    $this->assertTrue($response);
});
