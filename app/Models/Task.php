<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function tags()
    {
        return $this->hasMany(Tags::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
