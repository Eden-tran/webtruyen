<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Manga;

class Chapter extends Model
{
    use HasFactory;
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
}
