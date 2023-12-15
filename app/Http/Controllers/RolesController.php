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
        return redirect('/roles/list')->with([
            'message'=>' Role save successfully !!!',
            'success'=>true
        ]);
    }

    public function liste(){
        $listes = Roles::select("id",'role_name','code_role','status_role')->get();
        return view("roles.liste",compact("listes"));
    }

    public function edit($id = null){
        $role = Roles::select('id','role_name','code_role','status_role')->where('id','=',$id)->first();
        return view("roles.edit",compact("role"));
    }

    public function update(Request $request){

        $role = Roles::select('id','role_name','code_role','status_role')->where('id','=',(int)$request->cmd)->first();

        if(is_null($role)){
            return redirect('/roles/list')->with([
                'message'=>' This role is not exist ',
                'info'=>true,
            ]);
        }


        $role->role_name = $request->roleName;
        $role->code_role = $request->roleCode;
        $role->status_role = $request->status;
        $role->save();
        return redirect('/roles/list')->with([
            'message'=>' Role update successfully !!!',
            'success'=> true,
        ]);
    }
}
