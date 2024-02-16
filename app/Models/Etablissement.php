<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    protected $table ="gsc_etablissement";

    public function General()
    {
        return $this->hasOne(General::class, 'x_id');
    }


    public function Technique()
    {
        return $this->hasOne(Technique::class, 'x_id');
    }

    public function Filiere(){
        return $this->hasMany('App\Models\Filiere','school_id');
    }
}
