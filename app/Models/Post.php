<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'teachers_id', 'writing_types_id', 'storage_name', 'original_name', 'file_ext', 'mime_type', 'post_code', 'writing_date'
    ];
}
