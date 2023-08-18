<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\GroupSeeder;
use Database\Seeders\MangaSeeder;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\ChapterSeeder;
use Database\Seeders\CategorySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //super admin creat first
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MangaSeeder::class);
        $this->call(ChapterSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(ModuleSeeder::class);






        // \App\Models\User::factory(10)->create();
    }
}
