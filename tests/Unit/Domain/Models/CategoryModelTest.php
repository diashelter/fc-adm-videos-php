<?php
declare(strict_types=1);

use App\Models\CategoryModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

it('if use traits', function () {

    $traitsNeed = [
        HasFactory::class,
        SoftDeletes::class,
    ];
    $traitsUsed = array_keys(class_uses(new CategoryModel()));
    $this->assertEquals($traitsNeed, $traitsUsed);
});


it('incrementing is false', function () {
    $model = new CategoryModel();
    $this->assertFalse($model->incrementing);
});

it('has casts', function () {
    $castsNeed = [
        'id' => 'string',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];
    $model = new CategoryModel();
    $this->assertEquals($castsNeed,$model->getCasts());
});

it('fillables', function () {
    $fillablesEspected = [
        'id',
        'name',
        'description',
        'is_active',
    ];
    $model = new CategoryModel();
    $this->assertEquals($fillablesEspected, $model->getFillable());
});
