<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chapter;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'Chương 1',
            'manga_id' => '1',
            'active' => 2,
            'status' => 2,
            'slug' => 'chuong-1',
        ];
        $data2 = [
            'name' => 'Chương 2',
            'manga_id' => '1',
            'active' => 2,
            'status' => 2,
            'slug' => 'chuong-2',
        ];
        $data3 = [
            'name' => 'Chương 3',
            'manga_id' => '1',
            'active' => 2,
            'status' => 2,
            'slug' => 'chuong-3',
        ];
        Chapter::create($data);
        Chapter::create($data2);
        Chapter::create($data3);
    }
}
