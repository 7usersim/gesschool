<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = "gsc_roles";

    public function users(){
        return $this->hasMany('App\Models\User','roles_id');
    }
}
