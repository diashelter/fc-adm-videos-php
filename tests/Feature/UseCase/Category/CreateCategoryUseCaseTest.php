<?php

declare(strict_types=1);

use App\Models\CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Application\UseCase\Category\Create\CreateCategoryInput;
use Core\Application\UseCase\Category\Create\CreateCategoryUseCase;

it('should create category use case', function () {
    $useCase = new CreateCategoryUseCase(new CategoryEloquentRepository(new CategoryModel()));
    $categoryCreated = $useCase->execute(new CreateCategoryInput(name: 'Test'));
    expect('Test')->toBe($categoryCreated->name);
    $this->assertNotEmpty($categoryCreated->id);
    $this->assertDatabaseHas('categories', [
        'id' => $categoryCreated->id,
        'name' => $categoryCreated->name,
    ]);
});
