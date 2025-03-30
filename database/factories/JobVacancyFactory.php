<?php

namespace Database\Factories;

use App\Enums\CompaniesEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company'       => $this->faker->randomElement(CompaniesEnum::class),
            'title'         => $this->faker->sentence(),
            'description'   => $this->faker->text(),
            'job_title'     => $this->faker->jobTitle(),
            'contract_type' => $this->faker->randomElement([
                'Freiberuflich' => 'Freiberuflich',
                'Geringfügig'   => 'Geringfügig',
                'Teilzeit'      => 'Teilzeit',
                'Vollzeit'      => 'Vollzeit',
            ]),
            'salary'        => $this->faker->numberBetween(1000, 10000),
            'from'          => now()->subDays(rand(10, 365)),
            'to'            => now()->addDays(rand(10, 365)),
            'email'         => $this->faker->unique()->safeEmail(),
            'unit_text'     => $this->faker->randomElement([
                'Stündlich'   => 'Stündlich',
                'Wöchentlich' => 'Wöchnentlich',
                'Monatlich'   => 'Monatlich',
                'Jährlich'    => 'Jährlich',
            ]),
        ];
    }
}
