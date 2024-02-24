<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $table = 'gsc_evaluation';

    public function Evaluation(){
        return  $this->hasOne('App\Models\Note','id_evaluation');

    }
}
