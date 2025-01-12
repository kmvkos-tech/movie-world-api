<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_movie()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/movies', [
            'title' => 'Test Movie',
            'description' => 'Test Description',
            'publication_date' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $response->assertStatus(201);
    }

    public function test_can_get_movies()
    {
        Movie::factory()->count(5)->create();

        $response = $this->getJson('/api/movies');

        $response->assertStatus(200);
    }

    public function test_can_get_movies_by_user()
    {
        $user = User::factory()->create();
        Movie::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/movies/user/{$user->id}");

        $response->assertStatus(200);
    }
}
