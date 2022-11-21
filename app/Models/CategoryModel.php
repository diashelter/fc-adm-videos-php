<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'id',
        'name',
        'description',
        'is_active',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];
}
