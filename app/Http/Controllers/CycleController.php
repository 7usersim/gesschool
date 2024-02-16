<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CycleController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function getListCycle(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'name',
                1=>'code',
                2=>'description',
            );

            $totalData = Cycle::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = " SELECT id AS id,name AS name, code AS code, description AS description FROM gsc_cycle WHERE 1=1 ";

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (name LIKE "%'.htmlspecialchars($get_search).'%" OR code LIKE "%'.htmlspecialchars($get_search).'%" OR description LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            $listPermissions = DB::select($req);

            $totalFiltered = count($listPermissions);

            $data = array();

            if(!is_null($listPermissions)){
                foreach($listPermissions as $item){
                    $needData['name'] = $item->name;
                    $needData['code'] = $item->code;
                    $needData['description'] = $item->description;
                    $needData['options'] = "<a href='#' title=' Update cycle' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i clqss='fa fa-edit'></i> Edit</a> ";

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
                "name" => "required|max:20|unique:gsc_cycle,name",
                "code" => "required|max:8|unique:gsc_cycle,code",
                "description" => "required|max:30|unique:gsc_cycle,description",
            ]);
        } else{
            $V = \Validator::make($request->all(), [
                "name" => "required|max:20|unique:gsc_cycle,name,".$id,
                "code" => "required|max:8|unique:gsc_cycle,code,".$id,
                "description" => "required|max:30|unique:gsc_cycle,description,".$id,

            ]);
        }


        if ($V->fails()) {
            return response()->json([
                'message'=> $V->errors(),
                'error'=> true
            ], 404);
        }

        if($id == 0){
            $permission = new Cycle();
        }else{

            $permission = Cycle::where('id','=',$id)->first();
        }


        $permission->name = $request->name;
        $permission->code = $request->code;
        $permission->description = $request->description;

        $permission->save();
        if($id == 0 ){
            return response()->json([
                'success' => "cycle save Successfully",
                'error' => false
            ], 200);
        }else{
            return response()->json([
                'success' => "cycle update Successfully",
                'error' => false
            ], 200);
        }



    }

    public function liste(){
        $listes = Cycle::select("id",'name','code','description')->get();
        return view("cycle.index",compact("listes"));
    }

    // public function edit($id = null){
    //     $permission = PermissionsRoles::select('id','code','name')->where('id','=',$id)->first();
    //     return view("roles.edit",compact("permission"));
    // }

    public function update(Request $request){

        $permission = Cycle::select('id','name','code','description')->where('id','=',(int)$request->cmd)->first();

        if(is_null($permission)){
            return redirect('/cycle/list')->with([
                'message'=>' The cycle is wrong ',
                'info'=>true,
            ]);
        }
        $permission->name = $request->name;
        $permission->code = $request->code;
        $permission->description = $request->description;
        $permission->save();
        return redirect('/cycle/list')->with([
            'message'=>' cycle update successfully !!!',
            'success'=> true,
        ]);
    }
}
