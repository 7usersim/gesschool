<?php

namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\ClassesCourses;
use App\Models\Courses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursesByClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }
    public function index(){
        $classList = Classes::select('id', 'name')->get();

        // $req= "SELECT * FROM gsc_users
        //  INNER JOIN gsc_roles ON gsc_users.roles_id = gsc_roles.id
        //  WHERE gsc_roles.role_name = 'ENSEIGNANT' ";
        //  $TeacherList = DB::select($req);
        //  dd($result);

        $courseList = Courses::select('id', 'name')->get();
        $TeacherList = User::select('id', 'first_name')->get();

        return view('courses.courses',compact("classList","courseList","TeacherList"));
    }

    public function getlistCoursesByClass(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'m.credit',
                1=>'c.name',
                2=>'cl.name',
                3=>'u.firstname'
            );

            $totalData = ClassesCourses::count();
            // dd($totalData);
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = ConfigService::getCoursesByClasses();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (m.id LIKE "%'. htmlspecialchars($get_search).'%" OR m.credit LIKE "%'.htmlspecialchars($get_search).'%" OR cl.name LIKE "%'.htmlspecialchars($get_search).'%" OR c.name LIKE "%'.htmlspecialchars($get_search).'%" OR c.id LIKE "%'.htmlspecialchars($get_search).'%  OR u.firs_name LIKE "%'.htmlspecialchars($get_search).'%"  OR u.last_name LIKE "%'.htmlspecialchars($get_search).'%)';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            // dd($req);
            $listClassCourses = DB::select($req);
            // dd($listUsers);

            $totalFiltered = count($listClassCourses);
            // dd($totalFiltered);

            $data = array();

            if(!is_null($listClassCourses)){
                foreach($listClassCourses as $item){
                    $needData['credit'] = $item->Credit;
                    $needData['class'] = $item->NameClass;
                    $needData['course'] = $item->NameCourse;
                    $needData['teacher'] = $item->FirstNameTeacher;
                    $needData['options'] = "<a href='#' title=' Update courses' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i class='fa fa-edit'></i> Edit</a> ";
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
                "credit"=>"required|min:1|max:8",
                "courseID"=>"required",
                "classID"=>"required",
                "TeacherID"=>"required",
            ]);
        }else{
            $v = \Validator::make($request->all(),[
                "credit"=>"required|min:1|max:8",
                "courseID"=>"required",
                "classID"=>"required",
                "TeacherID"=>"required",

            ]);
        }

        if($v->fails()){
            return response()->json([
                'message' => $v->errors(),
                'error'=> true
            ], 404);
        }

        $courseID = $request->get("courseID");
        $classID = $request->get("classID");
        $existingCourses = ClassesCourses::where('course_id', $courseID)->where('class_id', $classID)->first();

        if( $existingCourses){
             $courses =  $existingCourses;
             return response()->json([
                'message'=> ['the course already exists '],
                'error'=> true
            ], 404);
        }
        
        try{
            if($id > 0){
            $courses = ClassesCourses::where('id','=',$id)->first();
        }else{
            $courses = new ClassesCourses();
        }
            $courses->course_id = $request->get("courseID");
            $courses->class_id = $request->get("classID");
            $courses->credit = $request->get("credit");
            $courses->teacher_id = $request->get("TeacherID");

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
