<?php

declare(strict_types=1);

use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Application\UseCase\CastMember\Create\CreateCastMemberInput;
use Core\Application\UseCase\CastMember\Create\CreateCastMemberUseCase;

it('should create cast member use case', function () {
    $useCase = new CreateCastMemberUseCase(new CastMemberEloquentRepository());
    $castMemberCreated = $useCase->execute(new CreateCastMemberInput(name: 'Test', type: '1'));
    expect('Test')->toBe($castMemberCreated->name);
    $this->assertNotEmpty($castMemberCreated->id);
    $this->assertDatabaseHas('cast_members', [
        'id' => $castMemberCreated->id,
        'name' => $castMemberCreated->name,
        'type' => $castMemberCreated->type,
    ]);
});
