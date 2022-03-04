<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'teachers_id', 'writing_types_id', 'export_name', 'file_ext', 'cover_ext', 'post_code', 'writing_date'
    ];
}
