<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    protected $table = "gsc_roles";
    public $timestamps = false;

    public function users(){
        return $this->hasMany('App\Models\User','roles_id');
    }

    public function Permission(){
        return $this->belongsToMany('App\Models\PermissionsUsers');
    }
}
