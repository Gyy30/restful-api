<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Pastikan di dalam array ini semuanya menggunakan koma ( , ) bukan titik koma ( ; )
    protected $fillable = [
        'image',
        'title',
        'content',
    ];
}
