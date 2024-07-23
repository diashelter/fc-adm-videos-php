<?php

declare(strict_types=1);

use Core\Domain\Entity\Genre;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\ValueObject\Uuid;
use Ramsey\Uuid\Uuid as RamseyUuid;

test('test genre attributes', function () {
    $date = new DateTimeImmutable();
    $uuid = RamseyUuid::uuid4()->toString();
    $genre = new Genre(
        id: new Uuid($uuid),
        name: 'new genre',
        isActive: false,
        createdAt: $date,
    );

    $this->assertEquals($uuid, $genre->id());
    $this->assertEquals('new genre', $genre->name);
    $this->assertEquals(false, $genre->isActive());
    $this->assertEquals($date->format('Y-m-d H:i:s'), $genre->createdAt());
});

test('test entity genre created', function () {
    $genre = new Genre(
        name: 'new genre',
    );

    $this->assertNotEmpty($genre->id());
    $this->assertEquals('new genre', $genre->name);
    $this->assertTrue($genre->isActive());
    $this->assertNotEmpty($genre->createdAt());
});

test('test deactivate genre', function () {
    $genre = new Genre(name: 'new genre');

    $this->assertTrue($genre->isActive());
    $genre->deactivate();
    $this->assertFalse($genre->isActive());
});

test('test activate genre', function () {
    $genre = new Genre(name: 'new genre', isActive: false);

    $this->assertFalse($genre->isActive());
    $genre->activate();
    $this->assertTrue($genre->isActive());
});

test('test update genre', function () {
    $genre = new Genre(name: 'teste');
    $this->assertEquals('teste', $genre->name);
    $genre->update(name: 'name update');
    $this->assertEquals('name update', $genre->name);
});

test('validate exceptions genres', function () {
    expect(fn () => new Genre(name: 'te'))->toThrow(EntityValidationException::class);
});

test('validate exceptions update genres', function () {
    expect(fn () => (new Genre(name: 'teste'))->update('mi'))->toThrow(EntityValidationException::class);
});

test('add category to genres', function () {
    $categoryId = RamseyUuid::uuid4()->toString();
    $genre = new Genre(name: 'Genres');
    $this->assertIsArray($genre->categoriesId);
    $this->assertCount(0, $genre->categoriesId);
    $genre->addCategory($categoryId);
    $genre->addCategory($categoryId);
    $this->assertCount(2, $genre->categoriesId);
});

test('remove category to genres', function () {
    $categoryId = RamseyUuid::uuid4()->toString();
    $categoryId2 = RamseyUuid::uuid4()->toString();
    $categoryId3 = RamseyUuid::uuid4()->toString();
    $genre = new Genre(name: 'Genres');
    $genre->addCategory($categoryId);
    $genre->addCategory($categoryId2);
    $genre->addCategory($categoryId3);
    $this->assertCount(3, $genre->categoriesId);

    $genre->removeCategory($categoryId);
    $this->assertCount(2, $genre->categoriesId);
});
