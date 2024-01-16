<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PermissionsRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PermissionsRolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function getListPermission(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'code',
                1=>'name',
            );

            $totalData = PermissionsRoles::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = " SELECT id AS id, code AS code, name AS name FROM permissions_roles WHERE 1=1 ";

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (code LIKE "%'.htmlspecialchars($get_search).'%" OR name LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            $listPermissions = DB::select($req);

            $totalFiltered = count($listPermissions);

            $data = array();

            if(!is_null($listPermissions)){
                foreach($listPermissions as $item){
                    $needData['code'] = $item->code;
                    $needData['name'] = $item->name;
                    $needData['options'] = "<a href='#' title=' Update permission' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i clqss='fa fa-edit'></i> Edit</a> ";

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

    public function save(Request $request)
    {
        $id = intval($request->cmd);

        if($id == 0){
            $V = \Validator::make($request->all(), [
                "code" => "required|unique:permissions_roles,code",
                "name" => "required|unique:permissions_roles,name",
            ]);
        } else{
            $V = \Validator::make($request->all(), [
                "code" => "required|unique:permissions_roles,code,".$id,
                "name" => "required|unique:permissions_roles,name,".$id,
            ]);
        }


        if ($V->fails()) {
            return response()->json([
                'message'=> $V->errors(),
                'error'=> true
            ], 404);
        }

        if($id == 0){
            $permission = new PermissionsRoles();
        }else{

            $permission = PermissionsRoles::where('id','=',$id)->first();
        }


        $permission->code = $request->code;
        $permission->name = $request->name;

        $permission->save();
        if($id == 0 ){
            return response()->json([
                'success' => "Permission save Successfully",
                'error' => false
            ], 200);
        }else{
            return response()->json([
                'success' => "Permission update Successfully",
                'error' => false
            ], 200);
        }



    }

    public function liste(){
        $listes = PermissionsRoles::select("id",'code','name')->get();
        return view("permissions.index",compact("listes"));
    }

    public function edit($id = null){
        $permission = PermissionsRoles::select('id','code','name')->where('id','=',$id)->first();
        return view("roles.edit",compact("permission"));
    }

    public function update(Request $request){

        $permission = PermissionsRoles::select('id','code','name')->where('id','=',(int)$request->cmd)->first();

        if(is_null($permission)){
            return redirect('/permission/list')->with([
                'message'=>' The Permissions is wrong ',
                'info'=>true,
            ]);
        }
        $permission->code = $request->code;
        $permission->name = $request->name;
        $permission->save();
        return redirect('/permission/list')->with([
            'message'=>' Permission update successfully !!!',
            'success'=> true,
        ]);
    }
}

