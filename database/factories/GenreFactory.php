<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\CategoryModel;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
{
    protected $model = Genre::class;
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
            'is_active' => $this->faker->randomElement([true, false]),
            'created_at' => now(),
        ];
    }
}
