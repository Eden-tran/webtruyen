<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chapter;

class Manga extends Model
{
    public function chapter()
    {
        return $this->hasMany(Chapter::class);
    }
}
