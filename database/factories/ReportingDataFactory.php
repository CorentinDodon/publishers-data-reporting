<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportingData>
 */
class ReportingDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'requests' => fake()->randomNumber(4),
            'impressions' => fake()->randomNumber(3),
            'clicks' => fake()->randomNumber(3),
            'revenues' => fake()->randomFloat(2, 0, 2000),
            'report_date' => fake()->dateTimeBetween('-3 week')
        ];
    }
}
