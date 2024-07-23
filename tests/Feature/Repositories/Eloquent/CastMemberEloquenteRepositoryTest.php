<?php

declare(strict_types=1);

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Entity\CastMember;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginateInterface;

beforeEach(function () {
    $this->repository = new CastMemberEloquentRepository();
});

it('check implementation', function () {
    expect($this->repository)->toBeInstanceOf(CastMemberEloquentRepository::class);
});

it('should insert cast member', function () {
    $entity = CastMember::create(
        name: 'Test Genre',
        type: '1'
    );
    $entityGenre = $this->repository->insert($entity);
    expect($entityGenre)->toBeInstanceOf(CastMember::class);
    $this->assertEquals($entity->name, $entityGenre->name);
    $this->assertEquals($entity->id, $entityGenre->id);
    $this->assertDatabaseHas('cast_members', [
        'id' => $entity->id(),
        'name' => $entity->name,
        'type' => $entity->type->value,
    ]);
});

it('should return exception cast member not found find by id', function () {
    expect(function () {
        $this->repository->findById('invalid_id');
    })->toThrow(NotFoundException::class, 'Cast Member invalid_id Not Found');
});

it('should find by id cast member', function () {
    $castMemberModel = CastMemberModel::factory()->create();
    $castMember = $this->repository->findById($castMemberModel->id);
    $this->assertInstanceOf(CastMember::class, $castMember);
    $this->assertEquals($castMember->id, $castMemberModel->id);
    $this->assertEquals($castMember->name, $castMemberModel->name);
});

it('should find all cast members', function () {
    CastMemberModel::factory(10)->create();
    $listCastMembers = $this->repository->findAll();
    $this->assertCount(10, $listCastMembers);
});

it('test Find All With Filter', function () {
    CastMemberModel::factory()->count(10)->create([
        'name' => 'Teste',
    ]);
    CastMemberModel::factory()->count(10)->create();

    $castMember = $this->repository->findAll(
        filter: 'Teste'
    );
    $this->assertEquals(10, count($castMember));

    $castMemberDb = $this->repository->findAll();
    $this->assertEquals(20, count($castMemberDb));
});

it('should test pagination', function () {
    CastMemberModel::factory(20)->create();

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
    $castMemberEntity = CastMember::create(name: 'test', type: '2');
    expect(function () use ($castMemberEntity) {
        $this->repository->update($castMemberEntity);
    })->toThrow(NotFoundException::class, "Cast Member {$castMemberEntity->id()} Not Found");
});

it('Update a cast member', function () {
    $modelsCastMember = CastMemberModel::factory()->create();
    $castMemberEntity = $this->repository->findById($modelsCastMember->id);
    $castMemberEntity->update(name: 'test update');
    $castMemberUpdated = $this->repository->update($castMemberEntity);
    $this->assertInstanceOf(CastMember::class, $castMemberUpdated);
    $this->assertNotEquals($castMemberUpdated->name, $modelsCastMember->name);
    $this->assertEquals('test update', $castMemberUpdated->name);
    $this->assertDatabaseHas('cast_members', [
        'id' => $modelsCastMember->id,
        'name' => 'test update',
    ]);
});

it('test deleted id nor found Cast Member', function () {
    expect(function () {
        $this->repository->delete('invalid_id');
    })->toThrow(NotfoundException::class, 'Cast Member invalid_id Not Found');
});

it('should delete Cast Member', function () {
    $CastMemberDb = CastMemberModel::factory()->create();
    $response = $this->repository->delete($CastMemberDb->id);
    $this->assertSoftDeleted('cast_members', [
        'id' => $CastMemberDb->id,
    ]);
    $this->assertTrue($response);
});
