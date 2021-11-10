<?php

namespace Tests\Unit;

use App\Models\Album;
use App\Models\Artist;
use App\Models\User;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class AlbumTest extends TestCase
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
        $this->admin = User::where('email', 'admin@admin.com')->first();
        $this->user = User::where('email', 'user1@gmail.com')->first();
    }

    public function validDataProvider(): array
    {
        $this->refreshApplication();
        return [
            [
                ['name' => 'Album name', 'artist_id' => Artist::first()->id, 'release_date' => '1999-05-15']
            ]
        ];
    }

    public function invalidDataProvider(): array
    {
        $this->refreshApplication();
        return [

            [[
                ['artist_id' => Artist::first()->id, 'release_date' => '1999-05-15'],
                ['field' => 'name',
                    'error' => 'The name field is required.']
            ]],
            [[
                ['name' => 'Pavadinimas', 'artist_id' => -1, 'release_date' => '1999-05-15'],
                ['field' => 'artist_id',
                    'error' => 'Invalid artist_id']
            ]],
            [[
                ['name' => 'Pavadinimas', 'release_date' => '1999-05-15'],
                ['field' => 'artist_id',
                    'error' => 'The artist id field is required.']
            ]],
            [[
                ['name' => 'Pavadinimas', 'artist_id' => Artist::first()->id, 'release_date' => '1999/05/15'],
                ['field' => 'release_date',
                    'error' => 'The release date does not match the format Y-m-d.']
            ]],

        ];
    }

    public function test_index()
    {
        $response = $this->json('GET', route('albums.index'));
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $response = $this->json('GET', route('albums.show', Album::first()));
        $response->assertStatus(200);
    }

    public function test_show_not_found()
    {
        $response = $this->json('GET', route('albums.show', -1));
        $response->assertStatus(404);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_create_with_valid_data($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', route('albums.store'), $validData);

        $response->assertStatus(201);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_create_with_invalid_data($invalidData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', route('albums.store'), $invalidData[0]);
        $this->assertEquals($response['errors'][$invalidData[1]['field']][0], $invalidData[1]['error']);
        $response->assertStatus(422);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_not_admin_can_not_create_album($validData)
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', route('albums.store'), $validData);
        $response->assertStatus(401);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_update_with_valid_data($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', route('albums.update', Album::latest()->first()), $validData);
        $response->assertStatus(200);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_update_with_invalid_data($invalidData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', route('albums.update', Album::latest()->first()), $invalidData[0]);
        $this->assertEquals($response['errors'][$invalidData[1]['field']][0], $invalidData[1]['error']);
        $response->assertStatus(422);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_update_not_existing_album($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', route('albums.update', -1), $validData);
        $response->assertStatus(404);
    }

    public function test_delete()
    {
        $album = Album::latest()->first();
        $response = $this->actingAs($this->admin, 'api')
            ->json('DELETE', route('albums.destroy', $album));
        $response->assertStatus(204);
        $this->assertNull(Artist::find($album->id));
    }

    public function test_delete_not_existing_album()
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('DELETE', route('albums.destroy', -1));
        $response->assertStatus(404);
    }
}
