<?php

declare(strict_types=1);

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Application\UseCase\CastMember\ListAll\InputCastMembers;
use Core\Application\UseCase\CastMember\ListAll\ListCastMembersUseCase;

it('should list all cast members use case', function () {
    $castMembersDb = CastMemberModel::factory(20)->create();
    $useCase = new ListCastMembersUseCase(new CastMemberEloquentRepository());
    $response = $useCase->execute(new InputCastMembers());
    $this->assertCount(15, $response->items);
    $this->assertEquals(count($castMembersDb), $response->total);
});

it('should list cast members empty use case', function () {
    $useCase = new ListCastMembersUseCase(new CastMemberEloquentRepository());
    $response = $useCase->execute(new InputCastMembers());
    $this->assertCount(0, $response->items);
});
