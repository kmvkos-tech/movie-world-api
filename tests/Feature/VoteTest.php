<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;

class VoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_movie()
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();


        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/movies/{$movie->id}/vote", [
            'vote_type' => 'like',
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_hate_movie()
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/movies/{$movie->id}/vote", [
            'vote_type' => 'hate',
        ]);

        $response->assertStatus(201);
    }

    public function test_user_can_retract_vote()
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create();

        $this->actingAs($user, 'sanctum');

        // User votes for the movie
        $this->postJson("/api/movies/{$movie->id}/vote", [
            'vote_type' => 'like',
        ])->assertStatus(201);

        // Assert that the vote exists
        $this->assertDatabaseHas('votes', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'vote_type' => 'like',
        ]);

        // User retracts the vote
        $response = $this->postJson("/api/movies/{$movie->id}/vote", [
            'vote_type' => 'like',
        ]);

        $response->assertStatus(201);

        // Assert that the vote has been retracted
        $this->assertDatabaseMissing('votes', [
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'vote_type' => 'like',
        ]);
    }
}
