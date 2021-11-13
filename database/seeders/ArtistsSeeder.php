<?php

namespace Database\Seeders;

use App\Models\Artist;
use Illuminate\Database\Seeder;

class ArtistsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $artists = [
            0 => [
                'first_name' => 'Ed',
                'last_name' => 'Sheeran',
                'birth_date' => '1991-02-17',
            ],
            1 => [
                'first_name' => 'Billie',
                'last_name' => 'Eilish',
                'birth_date' => '2001-12-18',
            ],
        ];

        foreach ($artists as $artist){
            Artist::firstOrCreate($artist);
        }
    }
}
