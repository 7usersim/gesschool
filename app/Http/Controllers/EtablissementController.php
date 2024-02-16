<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\General;
use App\Models\Technique;
use Illuminate\Support\Facades\DB;

class EtablissementController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

   public function index(){
    $conf = Etablissement::count();
    //  $config = Etablissement::all();
    return view("etablissement.index",compact('conf'));
   }


   public function getList(Request $request){
       if($request->ajax()){
        $columns = array(
            0 => "name",
            1 => "adresse",
            2 => "code_postal",
            3 => "ville",
            4 => "pays",
            5 => "fax",
            6 => "telephone",
            7 => "email",
            8 => "site_web",
            9 => "type_ets",
            10 => "logo"
        );
        $totalData = Etablissement::count();

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $req = "SELECT `id` AS `id`, `name` AS `name`, `adresse` As `adresse`, `code_postal` As `code_postal`,`ville` AS `ville`, `pays` AS `pays`,`fax` AS `fax`,`telephone` AS `telephone`,`email` AS `email`, `site_web` AS `site_web`,`date_fondation` AS `date_fondation`,`type_ets` AS `type_ets`,`logo` AS `logo` FROM `gsc_etablissement` WHERE 1=1 ";

        if(empty($request->input("search.value"))){
            $get_search = $request->input("search.value");
            $req .= ' AND (name LIKE "%' . htmlspecialchars($get_search) . '%" OR adresse LIKE "%' . htmlspecialchars($get_search) . '%" OR code_postal LIKE "%' . htmlspecialchars($get_search) .'%" OR ville LIKE "%' . htmlspecialchars($get_search) .'%" OR pays LIKE "%' . htmlspecialchars($get_search) .'%" OR fax LIKE "%' . htmlspecialchars($get_search) .'%" OR telephone LIKE "%' . htmlspecialchars($get_search) .'%" OR email LIKE "%' . htmlspecialchars($get_search) .'%" OR site_web LIKE "%' . htmlspecialchars($get_search) .'%" OR date_fondation LIKE "%' . htmlspecialchars($get_search) .'%" OR type_ets LIKE "%' . htmlspecialchars($get_search) .'%" OR logo LIKE "%' . htmlspecialchars($get_search) .  '%")';
        }

        $req .= ' ORDER BY ' . $order . ' ' . $dir . ' LIMIT ' . $limit . ' OFFSET ' . $start;
        $listConfigs = DB::select($req);

        $totalFiltered = count($listConfigs);

        $data = array();
        if(!is_null($listConfigs)){
            foreach($listConfigs as $item){
                $needData['name'] = $item->name;
                $needData['adresse'] = $item->adresse;
                $needData['code_postal'] = $item->code_postal;
                $needData['ville'] = $item->ville;
                $needData['pays'] = $item->pays;
                $needData['fax'] = $item->fax;
                $needData['telephone'] = $item->telephone;
                $needData['email'] = $item->email;
                $needData['site_web'] = $item->site_web;
                $needData['date_fondation'] = $item->date_fondation;
                $needData['type_ets'] = $item->type_ets;
                $needData['logo'] = $item->logo;

                $needData['options'] = "<a href='#' title='Update etablissement' onclick='edit(". json_encode($item) .")' class='btn btn-primary btnUpdate' > <i class='fas fa-edit'></i> Edit</a>";
                $data[] = $needData;
            }
         }


        $json = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
        );

        echo json_encode($json);

       }
   }


    // public function Type(){
    //     $general = Etablissement::where('type_ets', 'general')->get();

    //     foreach ($general as $generals) {
    //         $ral = new General();
    //         $ral->x_id = $generals->id;
    //         $ral->save();
    //     }

    // }



   public function save(Request $request)
   {

        $id = intval($request->cmd);
        // dd($request->all());
        if (intval($request->get("cmd")) > 0){
            $V = \Validator::make($request->all(),[
                "name" => "required|unique:gsc_etablissement,name,". intval($request->get('cmd')),
                "adresse" => "required|unique:gsc_etablissement,adresse,". intval($request->get('cmd')),
                "code_postal" => "required|unique:gsc_etablissement,code_postal,". intval($request->get('cmd')),
                "ville" => "required",
                "pays" => "required",
                "fax" => "required|unique:gsc_etablissement,fax,". intval($request->get('cmd')),
                "telephone" => "required|unique:gsc_etablissement,telephone,". intval($request->get('cmd')),
                "email" => "required|unique:gsc_etablissement,email,". intval($request->get('cmd')),
                "site_web" => "required|unique:gsc_etablissement,site_web,". intval($request->get('cmd')),
                "date_fondation" => "required",
                "type_ets" => "required",
                "logo" => "required|unique:gsc_etablissement,logo,". intval($request->get('cmd')),
            ]);
        } else{
                $V = \Validator::make($request->all(),[
                    "name" => "required|unique:gsc_etablissement,name",
                    "adresse" => "required|unique:gsc_etablissement,adresse",
                    "code_postal" => "required|unique:gsc_etablissement,code_postal",
                    "ville" => "required",
                    "pays" => "required",
                    "fax" => "required|unique:gsc_etablissement,fax",
                    "telephone" => "required|unique:gsc_etablissement,telephone",
                    "email" => "required|unique:gsc_etablissement,email",
                    "site_web" => "required|unique:gsc_etablissement,site_web",
                    "date_fondation" => "required:gsc_etablissement,date_fondation",
                    "type_ets" => "required",
                    "logo" => "required|unique:gsc_etablissement,logo",
                ]);
            }
            if($V->fails()){
                return response()->json([
                    'message' => $V->errors(),
                    'error' => true
                ],404);
            }
                if($id == 0){
                    $config = new Etablissement();
                }else{
                    $config = Etablissement::where('id', '=', $id)->first();
                }

                $config->name = $request->name;
                $config->adresse = $request->adresse;
                $config->code_postal = $request->code_postal;
                $config->ville = $request->ville;
                $config->pays = $request->pays;
                $config->fax = $request->fax;
                $config->telephone = $request->telephone;
                $config->email = $request->email;
                $config->site_web = $request->site_web;
                $config->date_fondation = $request->date_fondation;
                $config->type_ets = $request->type_ets;
                $config->logo = $request->logo;

                // if($request->hasfile('logo')){
                //     $file = $request->file('logo');
                //     $name = $file->getClientOriginalName();
                //     $filename = time().'.'.$name;
                //     $file->move('uploads/etablissement/',$filename);
                //     $config->logo = $filename;

                //  }else{
                //     return $request;
                //     $config->logo ='';
                //  }

                $config->save();

                // if($config->type_ets === 'general') {

                //     General::create([
                //         'x_id' => $config->id,
                //     ]);
                // }elseif ($config->type_ets === 'technique') {
                //     Technique::create([
                //         'x_id' => $config->id,
                //     ]);
                // }

                if($id == 0){
                    return response()->json([
                        'success' => "establishment save Successfully",
                        'error' => false
                    ], 200);
                }else{
                    return response()->json([
                        'success' => "Establishment update successfully",
                        'error' => false
                    ], 200);
                }
            }

            public function liste(){
                $liste = Etablissement::select('id','name','adresse','code_postal','ville','pays','fax','telephone','email','site_web','date_fondation','type_ets','logo')->get();
                return view("etablissement.index",compact('liste'));

            }

            // public function listeConfig(){
            //        return view("configuration.liste2",compact('conf'));
            // }

            public function edit(Request $request){
                $config = Etablissement::select('*')->where('id', $request);
                return view("etablissement.index",compact('config'));

            }


    public function update(Request $request){
        // dd($request);
        $config = Etablissement::select('*')->where('id', '=', $request->id)->first();

        if(is_null($config)){
            return redirect("etablissement/index")->with("info", "establishment exist");
        }

        $config->name = $request->name;
        $config->adresse = $request->adresse;
        $config->code_postal = $request->code_postal;
        $config->ville = $request->ville;
        $config->pays = $request->pays;
        $config->fax = $request->fax;
        $config->telephone = $request->telephone;
        $config->email = $request->email;
        $config->site_web = $request->site_web;
        $config->date_fondation = $request->date_fondation;
        $config->type_ets = $request->type_ets;
        $config->logo = $request->logo;

        // if($request->hasfile('logo')){
        //     $file = $request->file('logo');
        //     $name = $file->getClientOriginalName();
        //     $filename = time().'.'.$name;
        //     $file->move('uploads/configuration/',$filename);
        //     $config->logo = $filename;

        //  }else{
        //     return $request;
        //     $config->logo ='';
        //  }


        $config->save();

        return redirect('/etablissement/index')->with('success', 'Etablissement mis à jour avec succèss');
        }
    }



