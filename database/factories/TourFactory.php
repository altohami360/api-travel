<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tour>
 */
class TourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {


        $travels = Travel::pluck('id')->values()->toArray();

        return [
            //'travel_id' => array_rand($travels),
            'name' => $this->faker->sentence(2),
            'starting_at' => $this->faker->date('Y-m-d H:i'),
            'ending_at' => $this->faker->date('Y-m-d H:i'),
            'price' => $this->faker->randomFloat(2, 10, 500)
        ];
    }
}
