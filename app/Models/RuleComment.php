<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuleComment extends Model
{
    protected $fillable = ['posts_id', 'state_flag', 'mentors_id', 'writing_rules_id', 'writing_details_id'];
}
