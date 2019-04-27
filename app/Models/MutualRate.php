<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutualRate extends Model
{
    protected $fillable = [
        'rate', 'posts_id', 'teachers_id'
        ];
}
