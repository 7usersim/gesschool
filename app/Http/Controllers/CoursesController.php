<?php

namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Courses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursesController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }
    public function index(){
        $TeacherLists = User::select('id', 'first_name')->get();

        return view('courses.index',compact("TeacherLists"));
    }

    public function getlistCourses(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'c.name',
                1=>'c.code',
                2=>'c.description',
                3=>'u.first_name'
            );

            $totalData = Courses::count();
            // dd($totalData);
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = ConfigService::getAllCourses();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (c.id LIKE "%'. htmlspecialchars($get_search).'%" OR c.name LIKE "%'.htmlspecialchars($get_search).'%" OR c.code LIKE "%'.htmlspecialchars($get_search).'%" OR c.description LIKE "%'.htmlspecialchars($get_search).'%" OR u.id LIKE "%'.htmlspecialchars($get_search).'%" OR u.first_name LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            // dd($req);
            $listCourses = DB::select($req);
            // dd($listUsers);

            $totalFiltered = count($listCourses);
            // dd($totalFiltered);

            $data = array();

            if(!is_null($listCourses)){
                foreach($listCourses as $item){
                    $needData['name'] = $item->NameCourse;
                    $needData['code'] = $item->Code;
                    $needData['teacher'] = $item->NameTeacher;
                    $needData['description'] = $item->Description;
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
        if(intval($request->get("cmd")) > 0){
            $v = \Validator::make($request->all(),[
                "name"=>"required|min:1|max:100|unique:gsc_courses,name,".$id,
                "teacherID"=>"required",
                "code"=>"required|unique:gsc_courses,code,".$id,
                "description"=>"required|max:20|",
            ]);
        }else{
            $v = \Validator::make($request->all(),[
                "name"=>"required|min:1|max:100|unique:gsc_courses,name",
                "teacherID"=>"required",
                "code"=>"required|unique:gsc_courses,code",
                "description"=>"required|max:20|",
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
            $courses = Courses::where('id','=',$id)->first();
        }else{
            $courses = new Courses();
        }
            // $classe = new Classes();
            $courses->id_teacher = $request->get("teacherID");
            $courses->code = $request->get("code");
            $courses->name = $request->get("name");
            $courses->description = $request->get("description");

            $courses->save();  

            $stat = ($id == 0)? "save" : "update";

            return response()->json([
                'message' => " Course " .$stat. " successfuly ",
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
