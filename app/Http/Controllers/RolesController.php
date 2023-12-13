<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public function index(){
        return view("roles.index");
    }

    public function save(Request $request){
        $v = \Validator::make($request->all(),[
            "roleName"=>"required|unique:gsc_roles,role_name",
            "roleCode"=>"required|unique:gsc_roles,code_role"
        ]);

        if($v->fails()){
            return back()->withErrors($v)->withInput();
        }

        $role = new Roles();
        $role->role_name = $request->roleName;
        $role->code_role = $request->roleCode;
        $role->status_role = $request->status;
        $role->save();
        return back()->with(['message'=>' Role save successfully !!!']);
    }
}
