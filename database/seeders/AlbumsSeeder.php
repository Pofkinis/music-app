<?php

namespace Database\Seeders;

use App\Models\Album;
use Illuminate\Database\Seeder;

class AlbumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $albums = [
            0 => [
                'name' => 'Divide',
                'artist_id' => 1,
                'release_date' => '2016-05-05'
            ],
            1 => [
                'name' => 'Equals',
                'artist_id' => 1,
                'release_date' => '2018-07-16'
            ],
            2 => [
                'name' => 'Happier Than Ever',
                'artist_id' => 2,
                'release_date' => '2019-01-20'
            ],
        ];

        foreach ($albums as $album){
            Album::firstOrCreate($album);
        }
    }
}
