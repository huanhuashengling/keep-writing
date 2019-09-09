<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceLog extends Model
{
    protected $fillable = [
        'teachers_id', 'resources_id', 'time', 'writing_types_id'
    ];
}
