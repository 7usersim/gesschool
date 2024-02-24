<?php

namespace App\Http\Controllers;

use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Evaluation;
use App\Models\Note;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Class_;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }

    public function index(){
        $ExamList = Evaluation::select('id','name')->get();
        $classList = Classes::select('id','name')->get();

        return view('evaluation.notes',compact('ExamList','classList'));
    }

    public function getListNote(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'note',
                1=>'id_evaluation',
                2=>'id_courses',
                3=>'id_students',
            );

            $totalData = Note::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');
            $req = ConfigService::getNotesStudents();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (n.id LIKE "%'. htmlspecialchars($get_search).'%" OR n.note LIKE "%'.htmlspecialchars($get_search).'%" OR s.firstname LIKE "%'.htmlspecialchars($get_search).'%")';

            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;

            $listNotes = DB::select($req);
            // dd($req);

            $totalFiltered = count($listNotes);
            $data = array();

            if(!is_null($listNotes)){
                foreach($listNotes as $item){
                    $needData['note'] = $item->Note;
                    $needData['evaluation'] = $item->NameExam;
                    // $needData['class'] = $item->NameClass;
                    $needData['course'] = $item->CourseName;
                    $needData['student'] = $item->LastNameStudent.' '.$item->FirstNameStudent;
                    $needData['options'] = "<a href='#' title=' Update Note' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i clqss='fa fa-edit'></i> Edit</a> ";

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
                "note" => "required|numeric|min:0|max:20",
                "EvaluationID" => "required|numeric",
                "StudentID" => "required|numeric",
                "CourseID" => "required",
            ]);
        } else{
            $V = \Validator::make($request->all(), [
                "note" => "required|numeric|min:0|max:20",
                "EvaluationID" => "required",
                "StudentID" => "required|numeric",
                "CourseID" => "required",

            ]);
        }


        if ($V->fails()) {
            return response()->json([
                'message'=> $V->errors(),
                'error'=> true
            ], 404);
        }

        $studentID = $request->get("StudentID");
        $examID = $request->get("EvaluationID");
        $courseID = $request->get("CourseID");
        $existingNote = Note::where('id_students', $studentID)->where('id_courses', $courseID)->where('id_evaluation', $examID)->first();

        if( $existingNote){
             $note =  $existingNote;
             return response()->json([
                'message'=> ['the note already exists '],
                'error'=> true
            ], 404);
        }

        if($id == 0){

                $note = new Note();
        }else{

            $note = Note::where('id','=',$id)->first();
        }

        $note->note =$request->get("note");
        $note->id_evaluation = $request->get("EvaluationID");
        $note->id_students = $request->get("StudentID");
        $note->id_courses = $request->get("CourseID");

        $note->save();
        if($id == 0 ){
            return response()->json([
                'success' => "Note save Successfully",
                'error' => false
            ], 200);
        }else{
            return response()->json([
                'success' => "Note update Successfully",
                'error' => false
            ], 200);
        }



    }

    public function liste(){
        $listes = Note::select("id",'note', 'id_evaluation','id_courses','id_students')->get();
        return view("evaluation.note",compact("listes"));
    }


    public function update(Request $request){

        $note = Note::select('id','note','id_evaluation','id_courses','id_students')->where('id','=',(int)$request->cmd)->first();

        if(is_null($note)){
            return redirect('/note/list')->with([
                'message'=>' The note doesn\'t exist',
                'info'=>true,
            ]);
        }
        $note->note =$request->get("note");;
        $note->id_evaluation = $request->get("EvaluationID");
        $note->id_students = $request->get("StudentID");
        $note->id_courses = $request->get("CourseID");


        $note->save();
        return redirect('/exam/list')->with([
            'message'=>' Exam update successfully !!!',
            'success'=> true,
        ]);
    }
    public function getStudentsByClasses(Request $request){
        $studentList = Student::where('id_classe','=',intval($request->get('selectedClass')))->orderBy('lastname')->get();
        return response()->json([
            'studentList'=>$studentList,
            'error' => false
        ], 200);
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

        // dd($result);
    }

    public function getNoteDetails(Request $request)
    {
        $noteId = intval($request->cmd);

        // $note = Note::findOrFail($noteId);
        $note=Note::where('id', '=', $noteId);
        // dd($note);

        return response()->json($note);
    }

    public function ListClassNote(){
        $classList = Classes::select('id','name')->get();
        $examList = Evaluation::select('id','name')->get();

        return view('evaluation.search',compact('classList','examList'));
    }

    public function SearchNoteByClasse(Request $request){

        // $classes = intval($request->get('classe'));
        $exam = intval($request->get('exam'));

        $result = " SELECT
        n.note AS Note,
        s.firstname AS FirstName,
        s.lastname AS LastName,
        e.name AS Exam,
        crs.course_id AS Course,
        cl.name AS Class,
        c.name AS Course,
        u.first_name AS TeacherFirstName,
        u.last_name AS TeacherLastName

        FROM gsc_notes AS n
        INNER JOIN gsc_evaluation AS e ON n.id_evaluation = e.id
        INNER JOIN gsc_classes_courses AS crs ON n.id_courses = crs.course_id
        INNER JOIN gsc_students AS s ON n.id_students = s.id
        INNER JOIN gsc_classes AS cl ON s.id_classe = cl.id
        INNER JOIN gsc_courses AS c ON crs.course_id = c.id
        INNER JOIN gsc_users AS u ON crs.teacher_id = u.id
        WHERE 1 = 1";

        if (!empty($classes)){
            $result .= " AND id_classe = ".$classes ;
        }
        if (!empty($exam)){
            $result .= " AND id_evaluation = ".$exam ;
        }

        $results= DB::select($result);
        dd($results);
        $allData = count($results);
        $data = array();
        if(!is_null($results)){
            foreach ($results as $item) {
                $needData['evaluation'] = $item->Exam;
                $needData['classe'] = $item->Class;
                $needData['course'] = $item->Course;
                $needData['teacher'] = $item->TeacherFirstName;
                $needData['student'] = $item->FirstName.' '.$item->LastName;
                $needData['note'] = $item->Note;
                // $needData['options'] = "<a href='#' title=' Update role' onclick='edit(".json_encode($item).")' class='btn btn-sm btn-primary btnUpdate'> <i class='fa fa-edit'></i> Edit</a> ";

               $datas[] = $needData;
            }
        }

        return response()->json([
            'datas'=>$datas,
            'error' =>false
        ],200);
    }


}
