<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StageCheck extends Model
{
    protected $fillable = [
        'check_date', 'writing_types_id', 'schools_id'
    ];
}
