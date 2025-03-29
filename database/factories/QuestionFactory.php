<?php

namespace Database\Factories;

use App\Enums\CompaniesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order'    => $this->faker->numberBetween(1, 100),
            'question' => $this->faker->sentence(),
            'answer'   => $this->faker->paragraph(),
            'company'  => $this->faker->randomElement(CompaniesEnum::class),
        ];
    }
}
