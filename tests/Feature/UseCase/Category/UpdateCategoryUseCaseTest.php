<?php
declare(strict_types=1);

use App\Models\CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Application\DTO\Category\CategoryUpdateInput;
use Core\Application\UseCase\Category\Update\UpdateCategoryUseCase;

it('should update category', function () {
    $categoryDb = CategoryModel::factory()->create();
    $useCase = new UpdateCategoryUseCase(new CategoryEloquentRepository(new CategoryModel()));
    $response = $useCase->execute(new CategoryUpdateInput(
        id: $categoryDb->id,
        name: 'name Updated',
        description: $categoryDb->description,
        isActive: $categoryDb->is_active,
    ));
    $this->assertEquals($categoryDb->id, $response->id);
    $this->assertEquals('name Updated', $response->name);
    $this->assertEquals($categoryDb->description, $response->description);
    $this->assertEquals($categoryDb->is_active, $response->is_active);
    $this->assertEquals($categoryDb->created_at, $response->created_at);

    $this->assertDatabaseHas('categories', [
        'name' => $response->name
    ]);
});
