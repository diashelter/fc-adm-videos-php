<?php

declare(strict_types=1);

use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\EntityValidationException;

it('test Attributes', function () {
    $uuid = (string) \Ramsey\Uuid\Uuid::uuid4();

    $castMember = CastMember::restore(
        id: $uuid,
        name: 'Name',
        type: '2',
        createdAt: date('Y-m-d H:i:s')
    );

    $this->assertEquals($uuid, $castMember->id());
    $this->assertEquals('Name', $castMember->name);
    $this->assertEquals(CastMemberType::ACTOR, $castMember->type);
    $this->assertNotEmpty($castMember->createdAt());
});

it('test Attributes New Entity', function () {
    $castMember = CastMember::create(name: 'Name', type: '1');

    $this->assertNotEmpty($castMember->id());
    $this->assertEquals('Name', $castMember->name);
    $this->assertEquals(CastMemberType::DIRECTOR, $castMember->type);
    $this->assertNotEmpty($castMember->createdAt());
});

it('test Validation', function () {
    expect(function () {
        CastMember::create(name: 'ab', type: '1');
    })->toThrow(EntityValidationException::class);
});

it('test Exception Update', function () {
    expect(function () {
        $castMember = CastMember::create(name: 'ab', type: '1');

        $castMember->update(name: 'new name');

        $this->assertEquals('new name', $castMember->name);
    })->toThrow(EntityValidationException::class);
});

it('test Update', function () {
    $castMember = CastMember::create(name: 'name', type: '1');

    $this->assertEquals('name', $castMember->name);

    $castMember->update(name: 'new name');

    $this->assertEquals('new name', $castMember->name);
});
