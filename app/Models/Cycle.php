<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    protected $table = "gsc_cycle";

    public function Filiere(){
        return $this->hasMany('App\Models\Filiere','cycle_id');
    }

    public function Student(){
        return $this->hasMany("App\Models\Student",'id_cycle' );

      }
}
