<?php
declare(strict_types=1);

use App\Models\CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Application\UseCase\Category\CategoryInput;
use Core\Application\UseCase\Category\List\ListCategoryUseCase;

it('should list category use case', function () {
    $categoryDb = CategoryModel::factory()->create();
    $useCase = new ListCategoryUseCase(new CategoryEloquentRepository(new CategoryModel()));
    $response = $useCase->execute(new CategoryInput(id: $categoryDb->id));
    $this->assertEquals($categoryDb->id, $response->id);
    $this->assertEquals($categoryDb->name, $response->name);
    $this->assertEquals($categoryDb->description, $response->description);
    $this->assertEquals($categoryDb->is_active, $response->is_active);
    $this->assertEquals($categoryDb->created_at, $response->created_at);
});
