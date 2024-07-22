<?php

declare(strict_types=1);

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Application\UseCase\CastMember\Update\InputUpdateCastMemberDto;
use Core\Application\UseCase\CastMember\Update\UpdateCastMemberUseCase;

it('should update a cast member use case', function () {
    $castMemberDb = CastMemberModel::factory()->create();
    $useCase = new UpdateCastMemberUseCase(new CastMemberEloquentRepository());
    $response = $useCase->execute(new InputUpdateCastMemberDto(
        id: $castMemberDb->id,
        name: 'New Name',
        type: '1',
    ));
    expect($response->id)->toBe($castMemberDb->id);
    expect($response->name)->toBe('New Name');
});
