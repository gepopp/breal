<?php

namespace Database\Factories;

use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ServiceCard>
 */
class ServiceCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $icons = collect(Heroicon::cases())
            ->filter(fn ($icon) => ! str_starts_with($icon->name, 'Outlined'))
            ->pluck('value')
            ->toArray();

        return [
            'order' => $this->faker->numberBetween(1, 100),
            'name' => [
                'de' => $this->faker->words(3, true),
                'en' => $this->faker->words(3, true),
            ],
            'text' => [
                'de' => $this->faker->sentence(12),
                'en' => $this->faker->sentence(12),
            ],
            'icon' => $this->faker->randomElement($icons),
            'type' => $this->faker->randomElement(['service', 'feature']),
        ];
    }

    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'service',
        ]);
    }

    public function feature(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'feature',
        ]);
    }
}
