<?php

namespace Database\Factories;

use App\Enums\CompaniesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'name'    => $this->faker->word(),
            'company' => $this->faker->randomElement(CompaniesEnum::class),
            'order'   => $this->faker->numberBetween(1, 100),
        ];
    }
}
