<?php

declare(strict_types=1);

use App\Models\CategoryModel;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginateInterface;

beforeEach(function () {
    $this->repository = new CategoryEloquentRepository(new CategoryModel());
});

it('should insert category', function () {
    $createdAt = new DateTimeImmutable();
    $entity = new Category(
        name: 'Test Category',
        createdAt: $createdAt->sub(new DateInterval('P1D')),
    );
    $entityCategory = $this->repository->insert($entity);
    $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
    expect($entityCategory)->toBeInstanceOf(Category::class);
    $this->assertDatabaseHas('categories', [
        'name' => $entity->name,
    ]);
});

it('should find by id category', function () {
    $categoryModel = CategoryModel::factory()->create();
    $category = $this->repository->findById($categoryModel->id);
    $this->assertInstanceOf(Category::class, $category);
    $this->assertEquals($category->id, $categoryModel->id);
    $this->assertEquals($category->name, $categoryModel->name);
    $this->assertEquals($category->description, $categoryModel->description);
    $this->assertEquals($category->isActive, $categoryModel->is_active);
});

it('should return exception category not found find by id', function () {
    expect(function () {
        $category = $this->repository->findById('invalid_id');
    })->toThrow(NotFoundException::class, 'category not found');
});

it('should find all categories', function () {
    CategoryModel::factory(10)->create();
    $listCategories = $this->repository->findAll();
    $this->assertCount(10, $listCategories);
});

it('should test pagination', function () {
    CategoryModel::factory(20)->create();

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
    expect(function () {
        $categoryEntity = new Category(name: 'test');
        $category = $this->repository->update($categoryEntity);
    })->toThrow(NotFoundException::class, 'category not found');
});

it('Update category', function () {
    $categoryModel = CategoryModel::factory()->create();
    $categoryEntity = new Category(id: $categoryModel->id, name: 'test update');
    $categoryUpdated = $this->repository->update($categoryEntity);
    $this->assertInstanceOf(Category::class, $categoryUpdated);
    $this->assertNotEquals($categoryUpdated->name, $categoryModel->name);
    $this->assertEquals('test update', $categoryUpdated->name);
});

it('test deleted id nor found category', function () {
    expect(function () {
        $this->repository->delete('invalid_id');
    })->toThrow(NotfoundException::class, 'category not found');
});

it('should delete category', function () {
    $categoryDb = CategoryModel::factory()->create();
    $response = $this->repository->delete($categoryDb->id);
    $this->assertTrue($response);
});
