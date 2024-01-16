<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionsUsers extends Model
{
    protected $table = "permissions_users";

    public function PermissionsRoles()
    {
        return $this->belongsToMany('App\Models\PermissionsRoles');
    }

    public function PermissionRoles()
    {
        return $this->belongsToMany('App\Models\Roles');
    }
}
