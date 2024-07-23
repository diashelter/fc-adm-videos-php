<?php

declare(strict_types=1);

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Application\UseCase\CastMember\List\ListCastMemberUseCase;

it('should list cast member use case', function () {
    $castMemberDb = CastMemberModel::factory()->create();
    dump($castMemberDb);
    $useCase = new ListCastMemberUseCase(new CastMemberEloquentRepository());
    $castMemberOutput = $useCase->execute($castMemberDb->id);
    expect($castMemberDb->id)->toBe($castMemberOutput->id);
    expect($castMemberDb->name)->toBe($castMemberOutput->name);
    expect((string) $castMemberDb->type)->toBe($castMemberOutput->type);
});
