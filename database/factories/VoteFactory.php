<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vote>
 */
class VoteFactory extends Factory
{
    protected $model = Vote::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => Movie::factory(), // Will be overridden in the seeder
            'user_id' => User::inRandomOrder()->first()->id, // Random existing user
            'vote_type' => $this->faker->randomElement(['like', 'hate']),
        ];
    }
}
