public function save(Request $request){
    $id = intval($request->cmd);

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

    if ($V->fails()) {
        return response()->json([
            'message' => $V->errors(),
            'error' => true
        ], 404);
    }

    try {
        // Vérifier si l'étudiant existe déjà dans la base de données
        $existingPayment = FraisDeScolarite::where('student_id', $request->get("studentID"))->first();

        if ($existingPayment) {
            // Mettre à jour les champs de l'enregistrement existant
            $existingPayment->class_id = $request->get("classID");
            $existingPayment->paid = $request->get("paid");
            $existingPayment->left_to_pay = $request->get("left_to_pay");
            $existingPayment->payment_date = $request->get("payment_date");
            $existingPayment->payment_method = $request->get("payment_method");
            $existingPayment->payment_reference = $request->get("payment_reference");
            $existingPayment->payment_status = $request->get("payment_status");
            $existingPayment->save();

            $stat = "update";
        } else {
            // Créer un nouvel enregistrement
            $payment = new FraisDeScolarite();
            $payment->student_id = $request->get("studentID");
            $payment->class_id = $request->get("classID");
            $payment->paid = $request->get("paid");
            $payment->left_to_pay = $request->get("left_to_pay");
            $payment->payment_date = $request->get("payment_date");
            $payment->payment_method = $request->get("payment_method");
            $payment->payment_reference = $request->get("payment_reference");
            $payment->payment_status = $request->get("payment_status");
            $payment->save();

            $stat = "done";
        }

        return response()->json([
            'message' => "Payment $stat successful",
            'error' => false
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => $e->getMessage(),
            'error' => false
        ], 404);
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

        // Vérifier si un enregistrement avec ce studentID existe déjà
        $existingPayment = FraisDeScolarite::where('student_id', '=', $studentID)->first();

        if ($existingPayment) {
            // Mettre à jour l'enregistrement existant
            $payment = $existingPayment;
        } else {
            // Créer un nouvel enregistrement
            $payment = new FraisDeScolarite();
        }

        // Mettre à jour les valeurs des champs
        $payment->student_id = $studentID;
        $payment->class_id = $request->get("classID");
        $payment->paid = $request->get("paid");
        $payment->left_to_pay = $request->get("left_to_pay");
        $payment->payment_date = $request->get("payment_date");
        $payment->payment_method = $request->get("payment_method");
        $payment->payment_reference = $request->get("payment_reference");
        $payment->payment_status = $request->get("payment_status");

        // Enregistrez l'historique dans tous les cas
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

        // Enregistrez ou mettez à jour l'enregistrement
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



// public function save(Request $request){

//     $id = intval($request->cmd);
//     // dd($id);
//     if($id > 0){
//         $V = \Validator::make($request->all(),[
//             "classID"=>"required|numeric",
//             "studentID"=>"required|numeric|unique:gsc_frais,student_id,".$id,
//             "paid"=>"required|numeric|min:0",
//             "left_to_pay"=>"required|numeric|min:0",
//             "payment_date"=>"required",
//             "payment_method"=>"required",
//             "payment_reference"=>"required|numeric",
//             "payment_status"=>"required",
//         ]);
//     } else {
//         $V = \Validator::make($request->all(),[
//             "classID"=>"required|numeric",
//             "studentID"=>"required|numeric|unique:gsc_frais,student_id,".$id,
//             "paid"=>"required|numeric|min:0",
//             "left_to_pay"=>"required|numeric",
//             "payment_date"=>"required",
//             "payment_method"=>"required",
//             "payment_reference"=>"required|numeric",
//             "payment_status"=>"required",

//         ]);
//            }

//     if ($V->fails()) {
//         return response()->json([
//             'message' => $V->errors(),
//             'error' => true
//         ], 404);
//     }

//     try {
//         if ($id > 0) {

//             $payment = FraisDeScolarite::where('id', '=', $id)->first();

//             // $historiquePaiements = json_decode($payment->historique, true) ?? [];
//             // $nouvelHistorique = [
//             //     "student_id" => $payment->student_id,
//             //     "class_id" => $payment->class_id,
//             //     "amount" => $payment->amount,
//             //     "paid" => $payment->paid,
//             //     "left_to_pay" => $payment->left_to_pay,
//             //     "payment_date" => $payment->payment_date,
//             //     "payment_method" => $payment->payment_method,
//             //     "payment_reference" => $payment->payment_reference,
//             //     "payment_status" => $payment->payment_status
//             // ];
//             // $historiquePaiements[] = $nouvelHistorique;
//             // $payment->historique = json_encode($historiquePaiements);

//         } else {
//             $payment = new FraisDeScolarite();

//         }
//         $payment->student_id = $request->get("studentID");
//         $payment->class_id = $request->get("classID");
//         $payment->paid = $request->get("paid");
//         $payment->left_to_pay = $request->get("left_to_pay");
//         $payment->payment_date = $request->get("payment_date");
//         $payment->payment_method = $request->get("payment_method");
//         $payment->payment_reference = $request->get("payment_reference");
//         $payment->payment_status = $request->get("payment_status");
//         // $payment->save();

//         $historiquePaiements = json_decode($payment->historique, true) ?? [];

//         $nouvelHistorique = [
//             "student_id" => $payment->student_id,
//             "class_id" => $payment->class_id,
//             "paid" => $payment->paid,
//             "left_to_pay" => $payment->left_to_pay,
//             "payment_date" => $payment->payment_date,
//             "payment_method" => $payment->payment_method,
//             "payment_reference" => $payment->payment_reference,
//             "payment_status" => $payment->payment_status
//         ];

//         $historiquePaiements[] = $nouvelHistorique;

//         $nouvelHistoriqueJSON = json_encode($historiquePaiements);

//         $payment->historique = $nouvelHistoriqueJSON;
//         $payment->save();



//         $stat = ($id == 0) ? "done" : "update";

//         return response()->json([
//             'message' => "payment  ". $stat. "  Successfuly",
//             'error' => false
//         ], 200);

//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => $e->getMessage(),
//             'error' => false
//         ], 404);
//     }
// }


