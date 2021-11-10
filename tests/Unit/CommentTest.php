<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Song;
use App\Models\User;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class CommentTest extends TestCase
{
    protected $admin;
    protected $user1;
    protected $user2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
        $this->seed();
        $this->user1 = User::where('email', 'user1@gmail.com')->first();
        $this->user2 = User::where('email', 'user2@gmail.com')->first();
        $this->admin = User::where('email', 'admin@admin.com')->first();
    }

    public function validDataProvider(): array
    {
        $this->refreshApplication();
        return [
            [
                ['comment' => 'komentaras', 'song_id' => Song::first()->id]
            ]
        ];
    }

    public function invalidDataProvider(): array
    {
        $this->refreshApplication();
        return [
            [
                ['comment' => 'komentaras', 'song_id' => -1]
            ]
        ];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_user_can_create_comment($validData)
    {
        $response = $this->actingAs($this->user1, 'api')
            ->json('POST', route('comments.store'), $validData);

        $response->assertStatus(201);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_user_tries_to_comment_not_existing_song($invalidData)
    {
        $response = $this->actingAs($this->user1, 'api')
            ->json('POST', route('comments.store'), $invalidData);

        $response->assertStatus(422);
    }

    public function test_index_all_song_comments()
    {
        $response = $this->actingAs($this->user1, 'api')
            ->json('GET', route('comments.get-by-song', Song::first()));
        $response->assertStatus(200);
    }

    public function test_index_not_existing_song_comments()
    {
        $response = $this->actingAs($this->user1, 'api')
            ->json('GET', route('comments.get-by-song', -1));
        $response->assertStatus(404);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_user_can_delete_his_own_comment($validData)
    {
        $response = $this->actingAs($this->user1, 'api')
            ->json('POST', route('comments.store'), $validData)
            ->decodeResponseJson();
        $deletionResponse = $this->actingAs($this->user1, 'api')
            ->json('DELETE', route('comments.destroy', $response['id']));
        $deletionResponse->assertStatus(204);
        $this->assertNull(Comment::find($response['id']));
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_admin_can_delete_other_user_comment($validData)
    {
        $response = $this->actingAs($this->user1, 'api')
            ->json('POST', route('comments.store'), $validData);

        $deletionResponse = $this->actingAs($this->admin, 'api')
            ->json('DELETE', route('comments.destroy', $response['id']));
        $deletionResponse->assertStatus(204);
        $this->assertNull(Comment::find($response['id']));
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_user_can_not_delete_other_user_comments($validData)
    {
        $response = $this->actingAs($this->user1, 'api')
            ->json('POST', route('comments.store'), $validData);
        $this->refreshApplication();
        $deletionResponse = $this->actingAs($this->user2, 'api')
            ->json('DELETE', route('comments.destroy', $response['id']));
        $deletionResponse->assertStatus(401);
        $this->assertNotNull(Comment::find($response['id']));
    }
}
