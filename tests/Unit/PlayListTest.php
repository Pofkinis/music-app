<?php

namespace Tests\Unit;

use App\Models\Song;
use App\Models\User;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class PlayListTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
        $this->seed();
        $this->user= User::where('email', 'user1@gmail.com')->first();

    }

    public function test_user_can_add_song_to_playlist()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', route('song.add-to-list', Song::first()));

        $response->assertStatus(200);
    }

    public function test_user_can_remove_song_from_playlist()
    {
        $this->actingAs($this->user, 'api')
            ->json('GET', route('song.add-to-list', Song::first()));
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', route('song.remove-from-list', Song::first()));

        $response->assertStatus(200);
    }

    public function test_user_removes_not_existing_song_from_playlist()
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('GET', route('song.remove-from-list', -1));

        $response->assertStatus(404);
    }
}
