<?php

namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\TimeTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeTableController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index(){
        $classList = Classes::select('id','name')->get();

        return view('time_table.index',compact('classList'));
    }

    public function getListTime(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'date',
                1=>'starting_hour',
                2=>'closing_hour',
                3=>'id_courses',
                4=>'id_classes',
            );

            $totalData = TimeTable::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $req = ConfigService::getTimeClasses();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (t.id LIKE "%'.htmlspecialchars($get_search).'%" OR t.starting_hour LIKE "%'.htmlspecialchars($get_search).'%" OR t.closing_hour LIKE "%'.htmlspecialchars($get_search).'%" OR crs.name LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            $listTime = DB::select($req);

            $totalFiltered = count($listTime);

            $data = array();

            if(!is_null($listTime)){
                foreach($listTime as $item){
                    $needData['date'] = $item->Date;
                    $needData['starting_hour'] = $item->Start;
                    $needData['closing_hour'] = $item->End;
                    // $needData['classe'] = $item->NameClass;
                    // $needData['course'] = $item->CourseName;
                    $needData['options'] = "<a href='#' title=' Update time table' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i clqss='fa fa-edit'></i> Edit</a> ";

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
                "date" => "required",
                "starting_hour" => "required",
                "closing_hour" => "required",
                "CourseID" => "required|numeric",
                "ClassID" => "required|numeric",
            ]);
        } else{
            $V = \Validator::make($request->all(), [
                "date" => "required",
                "starting_hour" => "required",
                "closing_hour" => "required",
                "CourseID" => "required|numeric",
                "ClassID" => "required|numeric",

            ]);
        }


        if ($V->fails()) {
            return response()->json([
                'message'=> $V->errors(),
                'error'=> true
            ], 404);
        }

        if($id == 0){
                $time = new TimeTable();
        }else{

            $time = TimeTable::where('id','=',$id)->first();
        }

        $time->date =$request->get("date");
        $time->starting_hour =$request->get("starting_hour");
        $time->closing_hour = $request->get("closing_hour");
        $time->id_course = $request->get("CourseID");
        $time->id_classe = $request->get("ClassID");

        $time->save();
        if($id == 0 ){
            return response()->json([
                'success' => "time save Successfully",
                'error' => false
            ], 200);
        }else{
            return response()->json([
                'success' => "time update Successfully",
                'error' => false
            ], 200);
        }



    }


    public function update(Request $request){

        $time = TimeTable::select('id','date','starting_hour','closing_hour','id_course','id_classe')->where('id','=',(int)$request->cmd)->first();

        if(is_null($time)){
            return redirect('/time/list')->with([
                'message'=>' The record doesn\'t exist',
                'info'=>true,
            ]);
        }
        $time->date =$request->get("date");
        $time->starting_hour =$request->get("starting_hour");
        $time->closing_hour = $request->get("closing_hour");
        $time->id_course = $request->get("CourseID");
        $time->id_classe = $request->get("ClassID");

        $time->save();
        return redirect('/time/list')->with([
            'message'=>' record update successfully !!!',
            'success'=> true,
        ]);
    }

    public function getCoursesByClass(Request $request){
        $id = intval($request->get('selectedClass'));
        $req= " SELECT gsc_courses.name,gsc_courses.id
        FROM gsc_courses
        INNER JOIN gsc_classes_courses ON gsc_courses.id = gsc_classes_courses.course_id
        INNER JOIN gsc_classes ON gsc_classes.id = gsc_classes_courses.class_id
        WHERE gsc_classes.id = $id
        AND 1=1";
        $result = DB::select($req);

        return response()->json([
            'result'=>$result,
            'error' => false
        ], 200);

    }

    public function TimeList(){
        $classList = Classes::select('id','name')->get();

        return view('time_table.liste',compact('classList'));
    }

    public function AllTimeTable(Request $request){

        $classes = intval($request->get('classe'));

        $result = " SELECT gsc_time_tables.*,
         gsc_classes.name AS classe_name,
         gsc_classes_courses.course_name AS course_name
        FROM gsc_time_tables
        INNER JOIN gsc_classes ON gsc_time_tables.id_classe = gsc_classes.id
        INNER JOIN gsc_classes_courses ON gsc_time_tables.id_course = gsc_classes_courses.id
        WHERE 1 = 1";


        if (!empty($classes)){
            $result .= " AND id_classe = ".$classes ;
        }

        $results= DB::select($result);
        dd($results);
        $allData = count($results);
        $data = array();
        if(!is_null($results)){
            foreach ($results as $item) {
                $needData['course'] = $item->course_name;
                $needData['lastname'] = $item->lastname;
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
