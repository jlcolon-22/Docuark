<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity_log extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'task_id',
        'project_id',
        'user_id',
        'message'
    ];
}
