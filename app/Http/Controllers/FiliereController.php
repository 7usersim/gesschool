<?php

namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Cycle;
use App\Models\Etablissement;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiliereController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }
    public function index(){
        $cycleList = Cycle::select('id', 'name')->get();
        $SchoolLists = Etablissement::select('id', 'name')->get();
        $responsibleLists = User::select('id', 'first_name')->get();

        return view('filiere.index',compact("responsibleLists","SchoolLists","cycleList"));
    }

    // public function getCycle(Request $request){
    //     $cycle= Cycle::where('name','=',$request->id)->first();
    //     $school= Etablissement::where('name','=',$request->id)->first();
    //     $user= User::where('first_name','=',$request->id)->first();


    //     if( $cycle){

    //         $name = $cycle->id;
    //     }
    //     if( $school){

    //         $nameSchool = $school->id;
    //     }

    //     if( $user){

    //         $FirstName = $user->id;
    //     }


    //     return response()->json([
    //         'cycle' =>$name,
    //         'school' =>$nameSchool,
    //         'responsible' =>$FirstName,
    //         'error' => false
    //     ],200);
    // }

    public function getCycle(Request $request){
        $cycle = Cycle::where('name', '=', $request->id)->first();
        $school = Etablissement::where('name', '=', $request->id)->first();
        $user = User::where('first_name', '=', $request->id)->first();

        $name = $cycle;
        $nameSchool = $cycle;
        $FirstName = $cycle;

        if ($cycle) {
            $name = $cycle->id;
        }

        if ($school) {
            $nameSchool = $school->id;
        }

        if ($user) {
            $FirstName = $user->id;
        }

        return response()->json([
            'cycle' => $name,
            'school' => $nameSchool,
            'responsible' => $FirstName,
            'error' => false
        ], 200);
    }



    public function getlistFiliere(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'f.nom',
                1=>'f.code',
                2=>'f.description',
                3=>'f.responsable',
                4=> 'c.name',
                5=> 'e.name',
                6=> 'u.first_name'
            );

            $totalData = Filiere::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = ConfigService::getAllFilieresWithCycle();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (f.id LIKE "%'. htmlspecialchars($get_search).'%" OR f.nom LIKE "%'.htmlspecialchars($get_search).'%" OR f.code LIKE "%'.htmlspecialchars($get_search).'%" OR f.description LIKE "%'.htmlspecialchars($get_search).'%" OR c.name LIKE "%'.htmlspecialchars($get_search).'%" OR e.name LIKE "%'.htmlspecialchars($get_search).'%"OR u.first_name LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;

            $listUsers = DB::select($req);

            $totalFiltered = count($listUsers);

            $data = array();

            if(!is_null($listUsers)){
                foreach($listUsers as $item){
                    $needData['nom'] = $item->NameFiliere;
                    $needData['code'] = $item->CodeFiliere;
                    $needData['description'] = $item->DescriptionFiliere;
                    $needData['cycle'] = $item->NameCycle;
                    $needData['school'] = $item->NameSchool;
                    $needData['responsible'] = $item->FirstNameUser;
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

        $id = intval($request->cmd);
        // dd($id);
        if(intval($request->get("cmd"))  > 0){
            $V = \Validator::make($request->all(),[
                "description"=>"required",
                "cycleID"=>"required|numeric",
                "schoolID"=>"required|numeric",
                "responsibleID"=>"required|numeric",
                "nom"=>"required|alpha|min:2|max:40|unique:gsc_filiere,nom,".intval($request->get("cmd")) ,
                "code"=>"required|min:2|max:20|unique:gsc_filiere,code,".intval($request->get("cmd")) ,

            ]);
        } else {
            $V = \Validator::make($request->all(),[
                "description"=>"required",
                "cycleID"=>"required|numeric",
                "schoolID"=>"required|numeric",
                "responsibleID"=>"required|numeric",
                "nom"=>"required|alpha|min:2|max:40|unique:gsc_filiere,nom",
                "code"=>"required|min:2|max:20|unique:gsc_filiere,code",
            ]);
        }

        if ($V->fails()) {
            return response()->json([
                'message' => $V->errors(),
                'error' => true
            ], 404);
        }

        try {
            if ($id > 0) {

                $filiere = Filiere::where('id', '=', $id)->first();
            } else {
                $filiere = new Filiere();
                // dd($filiere);
            }
            // $filiere = new Filiere();
            $filiere->cycle_id = $request->get("cycleID");
            $filiere->school_id = $request->get("schoolID");
            $filiere->user_id = $request->get("responsibleID");
            $filiere->nom = $request->get("nom");
            $filiere->code = $request->get("code");
            $filiere->description = $request->get("description");
            $filiere->save();

            $stat = ($id == 0) ? "save" : "update";

            return response()->json([
                'message' => "field  ". $stat. "  Successfuly",
                'error' => false
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => false
            ], 404);
        }
    }

}






