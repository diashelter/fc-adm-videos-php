<?php
declare(strict_types=1);

use App\Models\CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Application\UseCase\Category\CategoryInput;
use Core\Application\UseCase\Category\Delete\DeleteCategoryUseCase;

it('should delete category use case', function () {
    $categoryDb = CategoryModel::factory()->create();
    $useCase = new DeleteCategoryUseCase(new CategoryEloquentRepository(new CategoryModel()));
    $response = $useCase->execute(new CategoryInput(id: $categoryDb->id));
    $this->assertTrue($response->success);
    $this->assertSoftDeleted($categoryDb);
});
