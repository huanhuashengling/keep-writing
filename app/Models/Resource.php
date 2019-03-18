<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'filename', 'file_ext', 'label', 'writing_types_id'
    ];
}
