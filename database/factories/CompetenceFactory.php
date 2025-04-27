<?php

namespace Database\Factories;

use App\Enums\CompaniesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CompetenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order'       => $this->faker->numberBetween(1, 100),
            'keyword'     => $this->faker->word(),
            'company'     => $this->faker->randomElement(CompaniesEnum::class),
            'name'        => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'body'        => $this->faker->realText(1200, 4),
            'on_landing'  => $this->faker->boolean(),
            'on_dropdown' => $this->faker->boolean(),
        ];
    }
}
