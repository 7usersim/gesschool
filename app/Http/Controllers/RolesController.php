<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function getListRole(Request $request){
        if($request->ajax()){
            $columns = array(
                0=> 'role_name',
                1=>'code_role',
                2=> 'status_role'
            );

            $totalData = Roles::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = " SELECT role_name AS Role, code_role AS Code, status_role AS Status FROM gsc_roles WHERE 1=1 ";

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (role_name LIKE "%'.htmlspecialchars($get_search).'%" OR code_role LIKE "%'.htmlspecialchars($get_search).'%" OR status_role LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            $listRoles = DB::select($req);

            $totalFiltered = count($listRoles);

            $data = array();

            if(!is_null($listRoles)){
                foreach($listRoles as $item){
                    $needData['role'] = $item->Role;
                    $needData['code'] = $item->Code;
                    if($item->Status == 'Actif') {
                        $needData['status'] = "<span class='badge badge-success'>". $item->Status."</span>";
                    }else{
                        $needData['status'] = "<span class='badge badge-danger'>". $item->Status."</span>";
                    }
                    $needData['options'] = "<a href='#' title=' Update role' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i clqss='fa fa-edit'></i> Edit</a> ";

                    $data[] = $needData;
                }
            }
            $json = array(
                "draw"=> intval($request->input('draw')),
                "recordsTotal"=> intval($totalData),
                "recordsFiltered"=> intval($totalFiltered),
                "data"=> $data,
            );

            echo json_encode($json);

        }
    }

    public function save(Request $request){
        $v = \Validator::make($request->all(),[
            "roleName"=>"required|unique:gsc_roles,role_name",
            "roleCode"=>"required|unique:gsc_roles,code_role",
            "status"=>"required"
        ]);

        if($v->fails()){
            return response()->json([
                'message' => $v->errors(),
                'error'=> true
            ], 404);
        }

        $role = new Roles();
        $role->role_name = $request->roleName;
        $role->code_role = $request->roleCode;
        $role->status_role = $request->status;
        $role->save();

        return response()->json([
            'message' => 'Role save successfully !!!',
            'error'=> false
        ], 200);
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
