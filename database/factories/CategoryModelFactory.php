<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\CategoryModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CategoryModel>
 */
class CategoryModelFactory extends Factory
{
    protected $model = CategoryModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => (string)Str::uuid(),
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(10),
            'is_active' => $this->faker->randomElement([true, false]),
        ];
    }
}
