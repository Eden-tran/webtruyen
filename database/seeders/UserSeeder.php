<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $data = [
            'name' => 'Eden',
            'email' => 'bin13199@gmail.com',
            'password' => Hash::make('12345678'),
            'active' => 1,
            'user_id' => 0,
            'avatar' => 'default.jpg',
            'group_id' => 1,
        ];
        $data2 = [
            'name' => 'Bin',
            'email' => 'tranngocquang13199@gmail.com',
            'password' => Hash::make('12345678'),
            'avatar' => 'default.jpg',
            'user_id' => 1,
            'active' => 1,
            'group_id' => 2,

        ];
        User::create($data);
        User::create($data2);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
