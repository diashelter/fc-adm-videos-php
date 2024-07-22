<?php

declare(strict_types=1);

use App\Models\CastMember as CastMemberModel;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Application\UseCase\CastMember\Delete\DeleteCastMemberUseCase;

it('should delete cast member use case', function () {
    $castMemberDb = CastMemberModel::factory()->create();
    $useCase = new DeleteCastMemberUseCase(new CastMemberEloquentRepository());
    $response = $useCase->execute($castMemberDb->id);
    $this->assertTrue($response->success);
    $this->assertSoftDeleted($castMemberDb);
});
