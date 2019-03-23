<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostRate extends Model
{
    protected $fillable = [
        'mentors_id', 'posts_id', 'rate'
    ];
}
