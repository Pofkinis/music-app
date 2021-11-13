<?php

namespace Tests\Unit;

use App\Models\Artist;
use App\Models\User;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class ArtistTest extends TestCase
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
        return [
            [
                ['first_name' => 'vardas', 'last_name' => 'pavarde', 'birth_date' => '1999-05-15']
            ]
        ];
    }

    public function invalidDataProvider(): array
    {
        return [

               [ [
                    ['last_name' => 'pavarde', 'birth_date' => '1999-05-15'],
                    ['field' => 'first_name',
                    'error' => 'The first name field is required.']
                ]],
                [[
                    ['first_name' => 'pavarde', 'birth_date' => '1999-05-15'],
                    ['field' => 'last_name',
                    'error' => 'The last name field is required.']
                ]],
               [ [
                    ['first_name' => 'Vardas', 'last_name' => 'pavarde', 'birth_date' => '1999-05:15'],
                    ['field' => 'birth_date',
                    'error' => 'The birth date does not match the format Y-m-d.']
                ]],

        ];
    }

    public function test_index()
    {
        $response = $this->json('GET', route('artists.index'));
        $response->assertStatus(200);
    }

    public function test_show()
    {
        $response = $this->json('GET', route('artists.show', Artist::first()->id));
        $response->assertStatus(200);
    }

    public function test_show_not_found()
    {
        $response = $this->json('GET', route('artists.show', -1));
        $response->assertStatus(404);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_create_with_valid_data($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', route('artists.store'), $validData);

        $response->assertStatus(201);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_create_with_invalid_data($invalidData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('POST', route('artists.store'), $invalidData[0]);
        $this->assertEquals($response['errors'][$invalidData[1]['field']][0], $invalidData[1]['error']);
        $response->assertStatus(422);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_not_admin_can_not_create_artist($validData)
    {
        $response = $this->actingAs($this->user, 'api')
            ->json('POST', route('artists.store'));
        $response->assertStatus(401);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_update_with_valid_data($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', '/api/artists/' . Artist::latest()->first()->id, $validData);
        $response->assertStatus(200);
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function test_update_with_invalid_data($invalidData)
    {
        $response = $this->actingAs($this->admin, 'api')
        ->json('PUT', route('artists.update', Artist::latest()->first()), $invalidData[0]);
        $this->assertEquals($response['errors'][$invalidData[1]['field']][0], $invalidData[1]['error']);
        $response->assertStatus(422);
    }

    /**
     * @dataProvider validDataProvider
     */
    public function test_update_not_existing_artist($validData)
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('PUT', route('artists.update', -1));
        $response->assertStatus(404);
    }

    public function test_delete()
    {
        $artist = Artist::latest()->first();
        $response = $this->actingAs($this->admin, 'api')
            ->json('DELETE', route('artists.destroy', $artist));
        $response->assertStatus(204);
        $this->assertNull(Artist::find($artist->id));
    }

    public function test_delete_not_existing_artist()
    {
        $response = $this->actingAs($this->admin, 'api')
            ->json('DELETE', route('artists.destroy', -1));
        $response->assertStatus(404);
    }
}
