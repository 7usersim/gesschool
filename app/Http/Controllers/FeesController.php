<?php

namespace App\Http\Controllers;
use App\Functions\ConfigService;
use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Cycle;
use App\Models\Filiere;
use App\Models\FraisDeScolarite;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PhpParser\Builder\Class_;

class FeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_admin');
    }
    public function index(){
        $studentList = Student::select('id', 'matricule')->get();
        $cycleList = Cycle::select('id', 'name')->get();
        $classeList = Classes::select('id', 'name')->get();
        return view('fees.index',compact("cycleList","studentList","classeList"));
    }
   public function Historique(){
    return view("fees.historique");
   }
    public function listHistorique(Request $request){
        $id = intval($request->id);
        $historique = FraisDeScolarite::where('student_id', '=', $id )->first();
        // dd($historique);
        return response()->json([
            'ListHistorique'=>$historique,
            'error' => false
        ], 200);
    }

    public function getStudentName(Request $request){
         $name = DB::select("SELECT firstname AS first,lastname AS last FROM gsc_students WHERE gsc_students.id = $request->id");
          return response()->json([
            'name'=>$name,
            'error'=>false,
          ],200);
        }

    public function getlistFees(Request $request){
        if($request->ajax()){
            $columns = array(
                0=>'f.paid',
                1=>'f.left_to_paid',
                2=>'f.payment_status',
                3=> 'f.payment_method',
                4=> 's.firstname',
                5=> 's.lastname',
            );

            $totalData = FraisDeScolarite::count();
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $req = ConfigService::getFees();

            if(!empty($request->input('search.value'))){
                $get_search = $request->input('search.value');
                $req .= ' AND (f.id LIKE "%'. htmlspecialchars($get_search).'%" OR s.id LIKE "%'.htmlspecialchars($get_search).'%" OR f.paid LIKE "%'.htmlspecialchars($get_search).'%" OR f.left_to_pay LIKE "%'.htmlspecialchars($get_search).'%" OR f.payment_date LIKE "%'.htmlspecialchars($get_search).'%" OR f.payment_method LIKE "%'.htmlspecialchars($get_search).'%" OR f.payment_status LIKE "%'.htmlspecialchars($get_search).'%"OR s.firstname LIKE "%'.htmlspecialchars($get_search).'%"OR s.lastname LIKE "%'.htmlspecialchars($get_search).'%"OR s.sexe LIKE "%'.htmlspecialchars($get_search).'%" OR f.payment_reference LIKE "%'.htmlspecialchars($get_search).'%" OR c.name LIKE "%'.htmlspecialchars($get_search).'%" OR c.id LIKE "%'.htmlspecialchars($get_search).'%" OR c.school_fees LIKE "%'.htmlspecialchars($get_search).'%")';
            }

            $req .= ' ORDER BY '. $order.' '.$dir.' LIMIT '.$limit. ' OFFSET '. $start;

            $listPay = DB::select($req);

            $totalFiltered = count($listPay);

            $data = array();

            if(!is_null($listPay)){
                foreach($listPay as $item){
                    $needData['paid'] = $item->Paid.' '.'xaf';
                    $needData['left_to_pay'] = $item->LeftToPay.' '.'xaf';
                    $needData['payment_date'] = $item->PaymentDate;
                    $needData['payment_method'] = $item->PaymentMethod;
                    $needData['payment_method'] = $item->PaymentMethod;
                    if(isset($item->historique) && !empty($item->historique)) {
                        $historique = json_decode($item->historique, true);
                        $needData['historique'] = $historique;
                    } else {
                        $needData['historique'] = [];
                    }
                    if($item->PaymentStatus == 'Paid') {
                        $needData['payment_status'] = "<span class='badge badge-success'>". $item->PaymentStatus."</span>";
                    }else{
                        $needData['payment_status'] = "<span class='badge badge-warning'>". $item->PaymentStatus."</span>";
                    }
                    $needData['student'] = $item->StudentFirstname.' '. $item->StudentLastname;
                    $needData['class'] = $item->NameClass;
                    $needData['payment_reference'] = $item-> PaymentReference;
                    $needData['sexe'] = $item->StudentSexe;
                    $needData['options'] = "
                    <a href='#' title=' historique' onclick='historique(".json_encode($item).")' class='btn btn-sm btn-secondary'> <i class='fa fa-eye'></i> Historique</a>
                     ";

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

        if($id > 0){
            $V = \Validator::make($request->all(), [
            "classID" => "required|numeric",
            "studentID" => "required|numeric",
            "paid" => "required|numeric|min:0",
            "left_to_pay" => "required|numeric|min:0",
            "payment_date" => "required",
            "payment_method" => "required",
            "payment_reference" => "required|numeric",
            "payment_status" => "required",

            ]);
        } else {
            $V = \Validator::make($request->all(), [
            "classID" => "required|numeric",
            "studentID" => "required|numeric",
            "paid" => "required|numeric|min:0",
            "left_to_pay" => "required|numeric|min:0",
            "payment_date" => "required",
            "payment_method" => "required",
            "payment_reference" => "required|numeric",
            "payment_status" => "required",

            ]);
        }

        if ($V->fails()) {
            return response()->json([
                'message' => $V->errors(),
                'error' => true
            ], 404);
        }

        try {
            $studentID = $request->get("studentID");

            $existingPayment = FraisDeScolarite::where('student_id', '=', $studentID)->first();

            if ($existingPayment) {
                $payment = $existingPayment;
            } else {
                $payment = new FraisDeScolarite();
            }

            $payment->student_id =$request->get('studentID');
            $payment->class_id =$request->get('classID');
            $payment->paid = $request->get("paid");
            $payment->left_to_pay = $request->get("left_to_pay");
            $payment->payment_date = $request->get("payment_date");
            $payment->payment_method = $request->get("payment_method");
            $payment->payment_reference = $request->get("payment_reference");
            $payment->payment_status = $request->get("payment_status");

            $historiquePaiements = json_decode($payment->historique, true) ?? [];
            $nouvelHistorique = [
                "student_id" => $payment->student_id,
                "class_id" => $payment->class_id,
                "paid" => $payment->paid,
                "left_to_pay" => $payment->left_to_pay,
                "payment_date" => $payment->payment_date,
                "payment_method" => $payment->payment_method,
                "payment_reference" => $payment->payment_reference,
                "payment_status" => $payment->payment_status
            ];
            $historiquePaiements[] = $nouvelHistorique;
            $payment->historique = json_encode($historiquePaiements);

            $payment->save();

            $stat = ($id == 0) ? "done" : "update";

            return response()->json([
                'message' => "payment  ". $stat. "  Successfuly",
                'error' => false
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error' => false
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

    public function getStudentsByClasses(Request $request){
        $studentList = Student::where('id_classe','=',intval($request->get('selectedClass')))->orderBy('lastname')->get();
        return response()->json([
            'studentList'=>$studentList,
            'error' => false
        ], 200);
    }

    public function getFeesByClasses(Request $request){

        $id = intval($request->selectedClass);
        $ClassFees = Classes::where('id', $id)->value('school_fees');
        // dd($studentList);
        return response()->json([
            'ClassFees'=>$ClassFees,
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


}

