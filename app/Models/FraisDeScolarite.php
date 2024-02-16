<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FraisDeScolarite extends Model
{
    protected $table ='gsc_frais';
    public function student(){
        return $this->hasMany('App\Models\Student','student_id');
    }

}
