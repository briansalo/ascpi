<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivationalMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'score_level',
        'image_url',
        'video_url',
        'message',
        'is_displayed',
    ];


}