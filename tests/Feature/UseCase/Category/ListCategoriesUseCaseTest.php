<?php
declare(strict_types=1);

use App\Models\CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Application\UseCase\Category\ListAll\ListCategoriesInput;
use Core\Application\UseCase\Category\ListAll\ListCategoriesUseCase;

it('should list categories empty use case', function () {
    $useCase = new ListCategoriesUseCase(new CategoryEloquentRepository(new CategoryModel()));
    $response = $useCase->execute(new ListCategoriesInput());
    $this->assertCount(0, $response->items);
});

it('should list categories use case', function () {
    $categoriesDb = CategoryModel::factory(20)->create();
    $useCase = new ListCategoriesUseCase(new CategoryEloquentRepository(new CategoryModel()));
    $response = $useCase->execute(new ListCategoriesInput());
    $this->assertCount(15, $response->items);
    $this->assertEquals(count($categoriesDb), $response->total);
});
