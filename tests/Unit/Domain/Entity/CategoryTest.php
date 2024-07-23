<?php

declare(strict_types=1);

use Core\Domain\Entity\Category;
use Core\Domain\Exception\EntityValidationException;
use Ramsey\Uuid\Uuid;

it('test attributes category', function () {
    $category = new Category(
        name: 'New Category',
        description: 'New Desc',
        isActive: true
    );

    $this->assertNotEmpty($category->id());
    $this->assertNotEmpty($category->createdAt());
    $this->assertEquals('New Category', $category->name);
    $this->assertEquals('New Desc', $category->description);
    $this->assertTrue($category->isActive());
});

it('test Activate category', function () {
    $category = new Category(
        name: 'New Category'
    );

    $this->assertFalse($category->isActive());
    $category->activate();
    $this->assertTrue($category->isActive());
});

it('test Deactivate category', function () {
    $category = new Category(
        name: 'New Category',
        isActive: true
    );

    $this->assertTrue($category->isActive());
    $category->deactivate();
    $this->assertFalse($category->isActive());
});

it('test update category', function () {
    $uuid = (string) Uuid::uuid4()->toString();

    $category = new Category(
        id: $uuid,
        name: 'New Category',
        description: 'New Desc',
        isActive: true
    );

    $category->update('change name', 'change description');
    $this->assertEquals($uuid, $category->id());
    $this->assertEquals('change name', $category->name);
    $this->assertEquals('change description', $category->description);
});

it('exception name', function () {
    expect(function () {
        $category = new Category(name: 'Ne', description: 'New Desc');
    })->toThrow(EntityValidationException::class, 'name caracteres should grant 3');
});

it('exception description', function () {
    expect(function () {
        $category = new Category(name: 'Name Ccategory', description: random_bytes(99999));
    })->toThrow(EntityValidationException::class);
});

it('Should creation category on description null', function () {
    $category = new Category(name: 'Name');
    $this->assertEquals('Name', $category->name);
    $this->assertEquals('', $category->description);
});
