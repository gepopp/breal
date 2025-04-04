<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(),
            'description' => $this->faker->paragraph(3),
            'tags'        => [$this->faker->word(), $this->faker->word(), $this->faker->word(), $this->faker->word()],
            'client_name' => $this->faker->name(),
            'testimonial' => $this->faker->sentence(),
        ];
    }
}
