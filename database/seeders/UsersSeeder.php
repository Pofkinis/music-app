<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            0 => [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'is_admin' => true,
                'password' => bcrypt('secret')
            ],
            1 => [
                'name' => 'User1',
                'email' => 'user1@gmail.com',
                'is_admin' => false,
                'password' => bcrypt('secret')
            ],
            2 => [
                'name' => 'User2',
                'email' => 'user2@gmail.com',
                'is_admin' => false,
                'password' => bcrypt('secret')
            ]
        ];

        foreach ($users as $user){
            User::create($user);
        }
    }
}
