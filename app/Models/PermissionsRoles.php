<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsRoles extends Model
{    protected $table = "permissions_roles";


     public function Permission(){
        return $this->belongsToMany('App\Models\PermissionsUsers');
    }

}
