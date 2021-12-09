<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;

class SongsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $songs = [
            0 => [
                'name' => 'Shape of You',
                'album_id' => 1,
                'link_to_song' => 'https://www.youtube.com/embed/JGwWNGJdvx8',
                'type' => 'pop'
            ],
            1 => [
                'name' => 'Perfect',
                'album_id' => 1,
                'link_to_song' => 'https://www.youtube.com/embed/2Vv-BfVoq4g',
                'type' => 'hip hop'
            ],
            2 => [
                'name' => 'Galway girl',
                'album_id' => 1,
                'link_to_song' => 'https://www.youtube.com/embed/87gWaABqGYs',
                'type' => 'pop'
            ],
            3 => [
                'name' => 'First time',
                'album_id' => 2,
                'link_to_song' => 'https://www.youtube.com/embed/ZcrP5Oqsx1U',
                'type' => 'hip hop'
            ],
            4 => [
                'name' => 'One',
                'album_id' => 2,
                'link_to_song' => 'https://www.youtube.com/embed/afusUyiYOwk',
                'type' => 'pop'
            ],
            5 => [
                'name' => 'Lego house',
                'album_id' => 2,
                'link_to_song' => 'https://www.youtube.com/embed/c4BLVznuWnU',
                'type' => 'pop'
            ],
            6 => [
                'name' => 'Happier Than Ever"',
                'album_id' => 3,
                'link_to_song' => 'https://www.youtube.com/embed/5GJWxDKyk3A',
                'type' => 'electropop'
            ],
            7 => [
                'name' => 'Overheated',
                'album_id' => 3,
                'link_to_song' => 'https://www.youtube.com/embed/vg6V2UWSjiM',
                'type' => 'electropop'
            ],
        ];

        foreach ($songs as $song) {
            Song::firstOrCreate($song);
        }
    }
}
