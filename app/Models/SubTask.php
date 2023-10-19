<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function download()
    {
        return $this->hasOne(TaskFile::class);
    }
    public function user(){
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
