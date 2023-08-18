<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manga;

class MangaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'user_id' => 1,
            'name' => 'One Piece',
            'another_name' => 'Vua hải tặc',
            'active' => '2',
            'author' => 'Oda Eiichiro',
            'describe' => 'Vua hải tặc, Đảo hải tặc, Đi tìm kho báu',
            'categories' => '1',
            'is_finished' => '1',
            'image_cover' => 'default.jpg',
            'slug' => 'one-piece',
        ];
        Manga::create($data);
        // Manga::create($data2);
    }
}
