<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Song;
use App\Models\User;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Laravel\Passport\ClientRepository;
use Tests\TestCase;

class SongTest extends TestCase
{
    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(
            ThrottleRequests::class
        );
        $this->seed();
        $this->user = User::where('email', 'user1@gmail.com')->first();
        $this->admin = User::where('email', 'admin@admin.com')->first();
    }

    public function validDataProvider(): array
    {
        $this->refreshApplication();
        return [
            [
                ['name' => 'Song name', 'album_id' => Album::first()->id, 'link_to_song' => 'LinkToSong', 'type' => 'pop']
            ]
        ];
    }

    public function invalidDataProvider(): array
    {
        $this->refreshApplication();
        return [
            [
                [
                    ['name' => 'Pavadinimas', 'album_id' => -1, 'link_to_song' => 'link', 'type' => 'pop'],
                    ['field' => 'album_id',
                        'error' => 'Invalid album_id'],
                ]
            ],

            [
                [
                    ['album_id' => Album::first()->id, 'link_to_song' => 'link', 'type' => 'pop'],
                    ['field' => 'name',
                        'error' => 'The name field is required.'],
                ]
            ],
            [
                [
                    ['name' => 'Pavadinimas', 'link_to_song' => 'link', 'type' => 'pop'],
                    ['field' => 'album_id',
                        'error' => 'The album id field is required.'],
                ]
            ],
            [
                [
                    ['name' => 'Pavadinimas', 'album_id' => Album::first()->id, 'type' => 'pop'],
                    ['field' => 'link_to_song',
                        'error' => 'The link to song field is required.'],
                ]
            ],
            [
                [
                    ['name' => 'Pavadinimas', 'album_id' => -1, 'link_to_song' => 'link', 'type' => 'pop'],
                    ['field' => 'album_id',
                        'error' => 'Invalid album_id'],
                ]
            ],
        ];
    }

    public function test_index()
    {
        $response = $this->json('GET', route('songs.index'));
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $response = $this->json('GET', route('songs.show', Song::first()));
        $response->assertStatus(200);
    }

    public function test_show_not_found()
    {
        $response = $this->json('GET', route('songs.show', -1));
        $response->assertStatus(404);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_create_with_valid_data($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', route('songs.store'), $validData);

        $response->assertStatus(201);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_create_with_invalid_data($invalidData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', route('songs.store'), $invalidData[0]);
        $this->assertEquals($response['errors'][$invalidData[1]['field']][0], $invalidData[1]['error']);
        $response->assertStatus(422);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_not_admin_can_not_create_album($validData)
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', route('songs.store'), $validData);
        $response->assertStatus(401);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_update_with_valid_data($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', route('songs.update', Song::first()), $validData);
        $response->assertStatus(200);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_update_with_invalid_data($invalidData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', route('songs.update', Song::first()), $invalidData[0]);
        $this->assertEquals($response['errors'][$invalidData[1]['field']][0], $invalidData[1]['error']);
        $response->assertStatus(422);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_update_not_existing_song($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', route('songs.update', -1), $validData);
        $response->assertStatus(404);
    }

    public function test_delete()
    {
        $song = Song::latest()->first();
        $response = $this->actingAs($this->admin, 'api')
            ->json('DELETE', route('songs.destroy', $song));
        $response->assertStatus(204);
        $this->assertNull(Artist::find($song->id));
    }

    public function test_delete_not_existing_song()
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('DELETE', route('songs.destroy', -1));
        $response->assertStatus(404);
    }
}
