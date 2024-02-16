<?php

namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Cycle;
use App\Models\Filiere;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct()
    {
      $this->middleware('is_admin');
    }

    public function getClassList(){
        $cycleList = Cycle::select('id','name')->get();
        return view('student.classlist',compact('cycleList'));
    }

    public function index(){
        $cycleList = Cycle::select('id','name')->get();
        return view('student.liste',compact("cycleList"));
    }

    

    public function getStudent(Request $request){
       if($request->ajax()){
        $columns = array(
            0=>'s.matricule',
            1=>'s.firstname',
            2=>'s.lastname',
            3=>'s.sexe',
            4=>'s.date_birth',
            5=>'s.email',
            6=>'s.parent_name',
            7=>'s.address',
            8=>'c.name',
            9=>'f.nom',
            10=>'e.name'
        );

        $totalData = Student::count();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $req = ConfigService::getAllStudents();

        if(!empty($request->input('search.value'))){
            $get_search = $request->input('search.value');
            $req .= ' AND (s.id LIKE "%'. htmlspecialchars($get_search).'%" OR s.firstname LIKE "%'.htmlspecialchars($get_search).'%" OR s.lastname LIKE "%'.htmlspecialchars($get_search).'%" OR s.sexe LIKE "%'.htmlspecialchars($get_search).'%" OR s.date_birth LIKE "%'.htmlspecialchars($get_search).'%" OR s.email LIKE "%'.htmlspecialchars($get_search).'%"OR s.parent_name LIKE "%'.htmlspecialchars($get_search).'%"OR s.address LIKE "%'.htmlspecialchars($get_search).'%" OR c.name LIKE "%'.htmlspecialchars($get_search).'%" OR c.id LIKE "%'.htmlspecialchars($get_search).'%" OR f.nom LIKE "%'.htmlspecialchars($get_search).'%" OR e.name LIKE "%'.htmlspecialchars($get_search).'%" OR e.id LIKE "%'.htmlspecialchars($get_search).'%" OR f.id LIKE "%'.htmlspecialchars($get_search).'%" )';

        }

        $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;


        $listStudents = DB::select($req);

        $totalFiltered = count($listStudents);

        $data = array();

        if(!is_null($listStudents)){
            foreach($listStudents as $item){
                $needData['matricule'] = $item->Matricule;
                $needData['firstname'] = $item->FirstName;
                $needData['lastname'] = $item->LastName;
                $needData['sexe'] = $item->Sexe;
                $needData['date_birth'] = $item->DateBirth;
                $needData['email'] = $item->Email;
                $needData['parent_name'] = $item->ParentName;
                $needData['address'] = $item->Address;
                $needData['cycle'] = $item->CycleName;
                $needData['field'] = $item->NameField;
                $needData['classe'] = $item->NameClass;
                $needData['options'] = "<a href='#' title='Update student' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'><i class='fa fa-edit'></i> Edit </a>";

                $data[] = $needData;
            }
        }
        $json = array(
            "draw"=> intval($request->input('draw')),
            "recordsTotal"=>intval($totalData),
            "recordsFiltered"=>intval($totalFiltered),
            "data"=>$data,
        );
        echo json_encode($json);

       }
    }

    public function save(Request $request){
        $id = intval($request->cmd);
        if(intval($request->get("cmd")) > 0){
            $V = \Validator::make($request->all(),[
                "firstname"=>"required|alpha|max:20|unique:gsc_students,firstname,".$id,
                "lastname"=>"required|alpha|max:20|unique:gsc_students,lastname,".$id,
                "sexe"=>"required",
                "address"=>"required",
                "date_birth"=>"required",
                "email"=>"required|max:20|unique:gsc_students,email,".$id,
                "parent_name"=>"required|alpha|max:20|",
                "CycleID"=>"required",
                "FieldID"=>"required",
                "ClassID"=>"required",
            ]);
        }else{
            $V = \Validator::make($request->all(),[
                "firstname"=>"required|alpha|max:20|unique:gsc_students,firstname",
                "lastname"=>"required|alpha|max:20|unique:gsc_students,lastname",
                "sexe"=>"required",
                "address"=>"required",
                "date_birth"=>"required",
                "email"=>"required|max:20|unique:gsc_students,email",
                "parent_name"=>"required|alpha|max:20|",
                "CycleID"=>"required",
                "FieldID"=>"required",
                "ClassID"=>"required",
            ]);
        }

        if($V->fails()){
            return response()->json([
                'message' => $V->errors(),
                'error' => true
            ],404);
        }
        try{
            if(intval($request->get("cmd")) <= 0){
                $student =Student::count();

                // $selectedFiliere = Filiere::All()
                // $selectedFiliereCode = substr($selectedFiliere->nom, 0, 2);

                $mat = "MATR".date('Y').sprintf("%04d",(intval($student) + 1));
                $student = new Student();
                $student->matricule = $mat;
            }else{
                $student = Student::where('id','=',intval($request->get("cmd")))->first();
            }
            $student->id_cycle = $request->get("CycleID");
            $student->id_field = $request->get("FieldID");
            $student->id_classe = $request->get("ClassID");
            $student->firstname = $request->get("firstname");
            $student->lastname = $request->get("lastname");
            $student->sexe = $request->get("sexe");
            $student->date_birth = $request->get("date_birth");
            $student->email = $request->get("email");
            $student->parent_name = $request->get("parent_name");
            $student->address = $request->get("address");
            $student->save();

            $stat = ($id == 0) ? "save" : "update";

            return response()->json([
                'message' =>" Student ".$stat." successfuly",
                'error' =>false
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage(),
                'error' =>false
            ], 404);
        }
    }

    public function getFiliereByCycle(Request $request){
        $fieldList = Filiere::where('cycle_id','=',intval($request->get('optionA')))->get();
        // dd($fieldList);
        return response()->json([
            'fieldList'=>$fieldList,
            'error' => false
        ], 200);
    }


    public function getClassByField(Request $request){

        $Fields = (intval($request->get('selectedField')));

        if(empty($request->get('selectedField'))){

            $classesList = Classes::all();
        }else{
            $classesList = Classes::where('field_id','=',$Fields)->get();
        }
        // dd($classesList);
        return response()->json([
            'classesList' => $classesList,
            'error' =>false
        ], 200);
    }

    public function AllStudent(Request $request){

        $cycle = intval($request->get('cycle'));
        $filiere = intval($request->get('field'));
        $classes = intval($request->get('classe'));
        $students = strval($request->get('firstname'));

        $result = " SELECT gsc_students.*,
         gsc_cycle.name AS cycle_name,
         gsc_filiere.nom AS filiere_name,
         gsc_classes.name AS classe_name
        FROM gsc_students
        INNER JOIN gsc_cycle ON gsc_students.id_cycle = gsc_cycle.id
        INNER JOIN gsc_filiere ON gsc_students.id_field = gsc_filiere.id
        INNER JOIN gsc_classes ON gsc_students.id_classe = gsc_classes.id
        WHERE 1 = 1";

        if (!empty($cycle)){
            $result .= " AND id_cycle = ".$cycle ;
        }
        if (!empty($filiere)){
            $result .= " AND id_field = ".$filiere ;
        }

        if (!empty($classes)){
            $result .= " AND id_classe = ".$classes ;
        }

        if (!empty($students)){
            $result .= ' AND gsc_students.firstname LIKE "%'.$students.'%" ' ;
        }

        $results= DB::select($result);
        $allData = count($results);
        $data = array();
        if(!is_null($results)){
            foreach ($results as $item) {
                $needData['firstname'] = $item->firstname;
                $needData['lastname'] = $item->lastname;
                $needData['sexe'] = $item->sexe;
                $needData['address'] = $item->address;
                $needData['cycle'] = $item->cycle_name;
                $needData['field'] = $item->filiere_name;
                $needData['class'] = $item->classe_name;
                $needData['options'] = "<a href='#' title=' Update role' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i class='fa fa-edit'></i> Edit</a> ";

               $datas[] = $needData;
            }
        }

        return response()->json([
            'datas'=>$datas,
            'error' =>false
        ],200);
    }


}
