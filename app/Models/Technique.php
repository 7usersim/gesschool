<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technique extends Model
{
    protected $table ="gsc_technique";

    public function Etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'x_id');
    }

}
