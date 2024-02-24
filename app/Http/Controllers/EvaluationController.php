<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Evaluation;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function getListExam(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'name',
                1=>'code',
                2=>'starting_date',
                3=>'ending_date',
            );

            $totalData = Evaluation::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = " SELECT id AS id,name AS name,code AS code, starting_date AS startingDate, ending_date AS endingDate FROM gsc_evaluation WHERE 1=1 ";

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (name LIKE "%'.htmlspecialchars($get_search).'%" OR code LIKE "%'.htmlspecialchars($get_search).'%" OR starting_date LIKE "%'.htmlspecialchars($get_search).'%" OR ending_date LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;
            $listExam = DB::select($req);

            $totalFiltered = count($listExam);

            $data = array();

            if(!is_null($listExam)){
                foreach($listExam as $item){
                    $needData['name'] = $item->name;
                    $needData['code'] = $item->code;
                    $needData['starting_date'] = $item->startingDate;
                    $needData['ending_date'] = $item->endingDate;
                    $needData['options'] = "<a href='#' title=' Update Exam' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i clqss='fa fa-edit'></i> Edit</a> ";

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
                "name" => "required|max:20|unique:gsc_evaluation,name",
                "code" => "required|max:20|unique:gsc_evaluation,code",
                "starting_date" => "required",
                "ending_date" => "required",
            ]);
        } else{
            $V = \Validator::make($request->all(), [
                "name" => "required|max:20|unique:gsc_evaluation,name,".$id,
                "code" => "required|max:20|unique:gsc_evaluation,code,".$id,
                "starting_date" => "required",
                "ending_date" => "required",

            ]);
        }


        if ($V->fails()) {
            return response()->json([
                'message'=> $V->errors(),
                'error'=> true
            ], 404);
        }

        if($id == 0){
            $exam = new Evaluation();
        }else{

            $exam = Evaluation::where('id','=',$id)->first();
        }


        $exam->name = $request->name;
        $exam->code = $request->code;
        $exam->starting_date = $request->starting_date;
        $exam->ending_date = $request->ending_date;

        $exam->save();
        if($id == 0 ){
            return response()->json([
                'success' => "Exam save Successfully",
                'error' => false
            ], 200);
        }else{
            return response()->json([
                'success' => "Exam update Successfully",
                'error' => false
            ], 200);
        }



    }

    public function liste(){
        $listes = Evaluation::select("id",'name', 'code','starting_date','ending_date')->get();
        return view("evaluation.index",compact("listes"));
    }


    public function update(Request $request){

        $exam = Evaluation::select('id','name','code','starting_date','ending_date')->where('id','=',(int)$request->cmd)->first();

        if(is_null($exam)){
            return redirect('/notes/list')->with([
                'message'=>' The exam is wrong ',
                'info'=>true,
            ]);
        }
        $exam->name = $request->name;
        $exam->code = $request->code;
        $exam->starting_date = $request->starting_date;
        $exam->ending_date = $request->ending_date;
        $exam->save();
        return redirect('/notes/list')->with([
            'message'=>' Exam update successfully !!!',
            'success'=> true,
        ]);
    }




}

