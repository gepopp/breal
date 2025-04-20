<?php

namespace Database\Factories;

use App\Enums\CompaniesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order'      => $this->faker->numberBetween(1, 100),
            'name'       => $this->faker->sentence(3),
            'points'     => [$this->faker->word(), $this->faker->word(), $this->faker->word()],
            'company'    => $this->faker->randomElement(CompaniesEnum::class),
            'on_landing' => true

        ];
    }
}
