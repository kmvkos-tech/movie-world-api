<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Movie;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 users
        $users = User::factory(20)
            ->has(
                Movie::factory(10) // Each user has 10 movies
            )
            ->create();

        // Add votes for each movie
        $movies = Movie::all();

        foreach ($movies as $movie) {
            // Fetch random users excluding the owner of the movie
            $voters = User::where('id', '!=', $movie->user_id)
                ->inRandomOrder()
                ->take(10) // 10 votes per movie
                ->get();

            foreach ($voters as $voter) {
                Vote::factory()->create([
                    'movie_id' => $movie->id,
                    'user_id' => $voter->id, // Ensure the voter isn't the movie owner
                ]);
            }
        }
    }
}
