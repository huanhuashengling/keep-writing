<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WordComment extends Model
{
    protected $fillable = [
        'good_word', 'bad_word', 'teachers_id', 'posts_id'
        ];
}
