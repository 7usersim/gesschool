<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{      protected $table = "gsc_filiere";



    public function cycle(){
        return $this->hasOne('App\Models\Filiere','cycle_id');
    }

    public function Etablissement(){
        return $this->hasOne('App\Models\Etablissement','school_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Classes(){
        return $this->hasMany('App\Models\Classes','field_id');
    }
    public function Classe(){
        return $this->hasMany('App\Models\Classes','user_id');
    }

    public function Student(){
        return  $this->hasMany("App\Models\Student",'id_cycle' );

      }

}
