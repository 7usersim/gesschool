<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $table ='gsc_courses';
    public function classes()
    {
        return $this->belongsToMany(Classes::class);
    }
}
