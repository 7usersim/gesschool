<?php

namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $rolesList = Roles::select('id', 'role_name')->where('status_role','=','Actif')->get();
        return view('users.index',compact("rolesList"));
    }

    public function save(Request $request){

        if(intval($request->get("cmd")) > 0){
            $v = \Validator::make($request->all(),[
                "first_name"=>"required",
                "last_name"=>"required",
                "roleID"=>"required|numeric",
                "country"=>"required",
                "city"=>"required",
                "adress"=>"required",
                "phone"=>"required",
                "email"=>"required|unique:ges_users,email,".intval($request->get("cmd")),
                "username"=>"required|unique:ges_users,username,".intval($request->get("cmd")),
                "password"=>"required",
                "status"=>"required"
            ]);
        }else{
            $v = \Validator::make($request->all(),[
                "first_name"=>"required",
                "last_name"=>"required",
                "country"=>"required",
                "roleID"=>"required|numeric",
                "city"=>"required",
                "adress"=>"required",
                "phone"=>"required",
                "email"=>"required|unique:gsc_users,email",
                "username"=>"required|unique:gsc_users,username",
                "password"=>"required",
                "status"=>"required"
            ]);
        }

        if($v->fails()){
            return response()->json([
                'message' => $v->errors(),
                'error'=> true
            ], 404);
        }


        try{
            if(intval($request->get("cmd")) <= 0){
                // generation du matricule MAT2023-00000
                $users = User::count();
                $mat = "MAT".date('Y').sprintf("%04d", (intval($users) + 1));

                    $user = new User();
                    $user->matricule = $mat;
            }else{
                $user = User::where('id','=',intval($request->get("cmd")))->first();
            }

            $user->roles_id = $request->get("roleID");
            $user->first_name = $request->get("first_name");
            $user->last_name = $request->get("last_name");
            $user->country = $request->get("country");
            $user->city = $request->get("city");
            $user->adresse = $request->get("adress");
            $user->username = $request->get("username");
            $user->phone_number = $request->get("phone");
            $user->email = $request->get("email");
            $user->status = $request->get("status");
            $user->slug = \Str::random(100);
            $user->password = \Hash::make($request->get("password"));
            $user->save();

            return response()->json([
                'message' => 'User save successfully !!!',
                'error'=> false
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'error'=> true
            ], 404);
        }


    }

    public function getListUser(Request $request){
        if($request->ajax()){
            $columns = array(
                0=> 'u.first_name',
                1=>'u.last_name',
                2=>'u.matricule',
                3=>'u.phone_number',
                4=>'u.email',
                5=> 'u.status',
                5=> 'r.role_name'
            );

            $totalData = User::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = ConfigService::getAllUsersWithRole();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (u.first_name LIKE "%'.htmlspecialchars($get_search).'%" OR r.role_name LIKE "%'.htmlspecialchars($get_search).'%" OR u.email LIKE "%'.htmlspecialchars($get_search).'%" OR u.last_name LIKE "%'.htmlspecialchars($get_search).'%"  OR u.phone_number LIKE "%'.htmlspecialchars($get_search).'%"  OR u.matricule LIKE "%'.htmlspecialchars($get_search).'%" OR u.status LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;

            $listUsers = DB::select($req);

            $totalFiltered = count($listUsers);

            $data = array();

            if(!is_null($listUsers)){
                foreach($listUsers as $item){
                    $needData['role'] = $item->Role;
                    $needData['firstname'] = $item->FirstName;
                    $needData['lastname'] = $item->LastName;
                    $needData['matricule'] = $item->Matricule;
                    $needData['phone'] = $item->Phone;
                    $needData['email'] = $item->Email;
                    if($item->STATUS == 'Actif') {
                        $needData['status'] = "<span class='badge badge-success'>". $item->STATUS."</span>";
                    }else{
                        $needData['status'] = "<span class='badge badge-danger'>". $item->STATUS."</span>";
                    }
                    $needData['options'] = "<a href='#' title=' Update user' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i clqss='fa fa-edit'></i> Edit</a> ";

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
}
