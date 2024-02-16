<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = "gsc_classes";

    public function Field(){
        return $this->hasOne('App\Models\Filiere','field_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','user_id');
    }

    public function Student(){
        $this->hasMany("App\Models\Student",'id_cycle' );

      }

      public function courses()
    {
        return $this->belongsToMany(Courses::class);
    }
}
