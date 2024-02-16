<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $table = "gsc_general";
    
    public function Etablissement()
    {
        return $this->belongsTo(Etablissement::class, 'x_id');
    }
}
