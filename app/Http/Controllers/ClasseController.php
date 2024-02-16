<?php
namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClasseController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }
    public function index(){
        $fieldList = Filiere::select('id', 'nom')->get();
        $TitulaireLists = User::select('id', 'first_name')->get();

        return view('classe.index',compact("fieldList","TitulaireLists"));
    }

    public function getAll(Request $request){
        $user = User::where('first_name', '=', $request->id)->first();
        $field = Filiere::where('nom', '=', $request->id)->first();

        $filiere = $field->id;
        $nameUser = $user->id;

        return response()->json([
            'user'=>$nameUser,
            'filiere'=>$filiere,
            'error' => false
        ], 200);
    }


    public function getlistClass(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'c.name',
                1=>'c.capacity',
                2=>'f.nom',
                3=> 'u.first_name'
            );

            $totalData = Classes::count();
            // dd($totalData);
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = ConfigService::getAllClasses();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (c.id LIKE "%'. htmlspecialchars($get_search).'%" OR c.name LIKE "%'.htmlspecialchars($get_search).'%" OR c.capacity LIKE "%'.htmlspecialchars($get_search).'%" OR f.nom LIKE "%'.htmlspecialchars($get_search).'%" OR u.first_name LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            // dd($req);
            $listUsers = DB::select($req);
            // dd($listUsers);

            $totalFiltered = count($listUsers);
            // dd($totalFiltered);

            $data = array();

            if(!is_null($listUsers)){
                foreach($listUsers as $item){
                    $needData['name'] = $item->NameClass;
                    $needData['capacity'] = $item->Capacity;
                    $needData['field'] = $item->NameField;
                    $needData['titulaire'] = $item->NameUser;
                    $needData['options'] = "<a href='#' title=' Update field' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i class='fa fa-edit'></i> Edit</a> ";
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
         $id = intval($request->get("cmd"));
        if(intval($request->get("cmd")) != 0){
            $v = \Validator::make($request->all(),[
                "capacity"=>"required|integer|min:1|max:100",
                "titulaireID"=>"required",
                "fieldID"=>"required",
                "name"=>"required|max:20|unique:gsc_classes,name,".intval($request->get('cmd')),
            ]);
        }else{
            $v = \Validator::make($request->all(),[
                "capacity"=>"required|integer|min:1|max:100",
                "titulaireID"=>"required",
                "fieldID"=>"required",
                "name"=>"required|max:20|unique:gsc_classes,name",
            ]);
        }

        if($v->fails()){
            return response()->json([
                'message' => $v->errors(),
                'error'=> true
            ], 404);
        }

        try{
            if($id > 0){
            $classe = Classes::where('id','=',$id)->first();
        }else{
            $classe = new Classes();
        }
            // $classe = new Classes();
            $classe->field_id = $request->get("fieldID");
            $classe->user_id = $request->get("titulaireID");
            $classe->capacity = $request->get("capacity");
            $classe->name = $request->get("name");

            $classe->save();

            $stat = ($id == 0)? "save" : "update";

            return response()->json([
                'message' => " Class " .$stat. " successfuly ",
                'error' => false
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'error' => false
            ],404);


            }


    }


}
