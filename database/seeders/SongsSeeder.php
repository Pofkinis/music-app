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
                'link_to_song' => 'https://www.youtube.com/watch?v=JGwWNGJdvx8&ab_channel=EdSheeran',
                'type' => 'pop'
            ],
            1 => [
                'name' => 'Perfect',
                'album_id' => 1,
                'link_to_song' => 'https://www.youtube.com/watch?v=2Vv-BfVoq4g&ab_channel=EdSheeran',
                'type' => 'hip hop'
            ],
            2 => [
                'name' => 'Galway girl',
                'album_id' => 1,
                'link_to_song' => 'https://www.youtube.com/watch?v=87gWaABqGYs&ab_channel=EdSheeran',
                'type' => 'pop'
            ],
            3 => [
                'name' => 'First time',
                'album_id' => 2,
                'link_to_song' => 'https://www.youtube.com/watch?v=ZcrP5Oqsx1U&ab_channel=LukeGittins',
                'type' => 'hip hop'
            ],
            4 => [
                'name' => 'One',
                'album_id' => 2,
                'link_to_song' => 'https://www.youtube.com/watch?v=afusUyiYOwk&ab_channel=DopeLyrics',
                'type' => 'pop'
            ],
            5 => [
                'name' => 'Lego house',
                'album_id' => 2,
                'link_to_song' => 'https://www.youtube.com/watch?v=c4BLVznuWnU&ab_channel=EdSheeran',
                'type' => 'pop'
            ],
            6 => [
                'name' => 'Happier Than Ever"',
                'album_id' => 3,
                'link_to_song' => 'https://www.youtube.com/watch?v=5GJWxDKyk3A&ab_channel=BillieEilishVEVO',
                'type' => 'electropop'
            ],
            7 => [
                'name' => 'Overheated',
                'album_id' => 3,
                'link_to_song' => 'https://www.youtube.com/watch?v=vg6V2UWSjiM&ab_channel=BillieEilishVEVO',
                'type' => 'electropop'
            ],
        ];

        foreach ($songs as $song) {
            Song::create($song);
        }
    }
}
