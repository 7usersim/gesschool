@extends("layouts.admin")
@section("title",__("List payement"))
@section("title_content",__("List payement"))
@section('css')
  <!-- DataTables -->
  <link href="{{asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

  <!-- Responsive datatable examples -->
  <link href="{{asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
<div class="col-md-12">
    @include('flash-message')
    <a href="#" class="btn btn-sm btn-primary add"> <i class="fa fa-plus"></i> Add payment</a>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4 class="text-white">{{__(" payement list")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                      <th scope="col">Class</th>
                    <th scope="col">Paid</th>
                    <th scope="col">Left to pay</th>
                    <th scope="col">Status</th>
                    <th scope="col">Method</th>
                    <th scope="col">Name</th>
                    <th scope="col">Options</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
              </table>
        </div>
    </div>
</div>
<!-- initiqlisqtion du ;odql -->
<div class="modal" tabindex="-1" role="dialog" id="formFieldID" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary ">
          <h5 class="modal-title text-white">Pay school fees</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="Field" name="Field" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="cmd" id="cmd" value="0">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="classe">{{__(" Class")}}</label>
                        <select name="classe" id="classe" class="form-control">
                            <option value="" disabled selected>{{__('Select a Class')  }}</option>
                            @foreach ($classeList as $classe)
                            <option value="{{$classe->id}}">{{$classe->name}}</option>
                            @endforeach
                        </select>
                    </div>
                        <div class="form-group col-md-4">
                            <label for="student">{{__(" Student")}}</label>
                            <select name="student" id="student" class="form-control">
                             <option value="" disabled selected>{{__('Select a student')  }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4 ">
                            <label for="school fees">{{__(" School fees")}}</label>
                            <input type="text" name="amount" id="amount" class="form-control" readonly>
                        </div>
                </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="paid"> Paid</label>
                            <input type="number" name="paid" required class="form-control" id="paid" placeholder="{{__('Enter value')}}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="left_to_pay">Amount to pay</label>
                            <input type="number" name="left_to_pay" required class="form-control" id="left_to_pay" placeholder="{{__('Enter value')}}" readonly>
                            </div>
                        <div class="form-group col-md-4 ">
                            <label for="Status">{{__(" Status")}}</label>
                            <select name="payment_status" id="payment_status" class="form-control">
                                <option value="" disabled selected>{{__('Select a status')  }}</option>
                            </select>
                        </div>

                </div>
                <div class="form-row">
                    <div class="form-group col-md-4 ">
                        <label for="Method">{{__(" Method")}}</label>
                        <select name="payment_method" id="payment_method" class="form-control">
                            <option value="" disabled selected>{{__('Select a method')  }}</option>
                            <option value="espece">{{__('Espece')  }}</option>
                            <option value="cheque">{{__('Cheque')  }}</option>
                            <option value="om">{{__('Orange Money')  }}</option>
                            <option value="mobile">{{__('Mobile Money')  }}</option>
                            <option value="eu">{{__('Express Union')  }}</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="Payment_reference">Payment reference</label>
                        <input type="text" name="payment_reference" required class="form-control" id="payment_reference" placeholder="{{__('Enter value')}}">
                     </div>
                    <div class="form-group col-md-4">
                        <label for="Payment_date">Payment date</label>
                        <input type="date" name="payment_date" required class="form-control" id="payment_date" placeholder="{{__('Enter value')}}">
                     </div>
                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary save">{{__(" Save")}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
         </div>
        </div>
      </div>

      <div class="modal" tabindex="-1" role="dialog" id="formHistorique" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary ">
              <h5 class="modal-title text-white">Historique</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        <table id="tech-companies-1" class="table table-striped">
                            <thead class=" bg-info text-white">
                            <tr>
                                {{-- <th data-priority="1">Class</th> --}}
                                <th data-priority="3">Paid</th>
                                <th data-priority="1">Left to pay</th>
                                <th data-priority="3">Payment Date</th>
                                <th data-priority="3">Method</th>
                                <th data-priority="6">Reference</th>
                                <th data-priority="6">Status</th>
                            </tr>
                            </thead>
                            <tbody id="tabcontent">

                           </tbody>
                        </table>
                    </div>

                </div>
             </div>
             </div>
            </div>
          </div>

    </div>
  </div>
</div>
@endsection

@section('js')
<!-- Required datatable js -->
<script src="{{asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">

 var table = $('#datatable').DataTable({
        lengthMenu:[[50,100,500, -1], [50, 100, 500, 'All']],
        processing: true,
        locale:"{{config('app.locale')}}",
        serverSide:true,
        ajax:{
            url:"{{url('/fees/getFees')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            {'data':'class'},
            {'data':'paid'},
            {'data':'left_to_pay'},
            {'data':'payment_status'},
            {'data':'payment_method'},
            {'data':'student'},
            {'data':'options'}
        ]
    });

    function sweet(){
        var t;
        Swal.fire({
            title: " Loading...",
            html: "<strong></strong> seconds.",
            timer: 2e3,
            onBeforeOpen: function() {
                Swal.showLoading(),
                t = setInterval(function() {
                }, 10000)
            },
            onClose: function() {
                clearInterval(t)
            }
        }).then(function(t) {
            t.dismiss === Swal.DismissReason.timer
        });

         }

         function Clean(){
            $("#cmd").val('');
            $('#student').val('');
            $('#payment_date').val('');
            $('#payment_reference').val('');
            // $('#amount').val('');
            $('#paid').val('');
            $('#left_to_pay').val('');
            $('#payment_status').val('');
            $('#payment_method').val('');
            $('#classe').val('');
         }

         function edit(fees){
            // console.log(fees);
            $("#cmd").val(fees.id);
            $('#payment_date').val(fees.PaymentDate);
            $('#payment_reference').val(fees.PaymentReference);
            $('#amount').val(fees.Amount);
            $('#paid').val(fees.Paid);
            $('#left_to_pay').val(fees.LeftToPay);
            $('#payment_status').val(fees.PaymentStatus);
            $('#payment_method').val(fees.PaymentMethod);
            $('#student').val(fees.idStudent);
            $('#classe').val(fees.IDClass);
            $('#formFieldID').modal({show:true, keyboard:false, backdrop:'static'});

         }

         function historique(student){
            // console.log(student.idStudent);
            $.ajax({
            type: "POST",
            url: "/student/historiqueFees",
            dataType:'json',
            data:{'id':student.idStudent},
            headers:{'X-CSRF-Token':csrfToken},
            success: function(results) {
                var resultTable = $("#tabcontent");
                resultTable.empty();
                let historique = JSON.parse(results.ListHistorique.historique);
                console.log(historique);
                historique.forEach(data => {
                content = `<tr>

                <td>${data.paid}</td>
                <td>${data.left_to_pay}</td>
                <td>${data.payment_date}</td>
                <td>${data.payment_method}</td>
                <td>${data.payment_reference}</td>
                <td>${data.payment_status}</td>
                </tr>`;
                resultTable.append(content);

                });
               }
          });

            $('#formHistorique').modal({show:true, keyboard:false, backdrop:'static'});

         }

                $("#classe").on('change',function(){
                    var selectedClass = $(this).val();
                    $.ajax({
                        url:'/student/getStudentByClass',
                        type:'GET',
                        data:{selectedClass:selectedClass},
                        success:function(optionD){
                            var selectedStudent = $('student');
                            selectedStudent.empty();
                            selectedStudent.append(`<option value="" selected disabled>Select a student</option>`);
                            optionD.studentList.forEach(data =>{
                                content =`<option value='${data.id}'>${data.firstname} ${data.lastname}</option>`;
                                $('#student').append(content);
                            })
                        },
                        error:function(error){
                            console.log("error:",error);
                        }
                    });
                });

                $("#classe").on('change', function () {
                    var selectedClass = $(this).val();
                    $.ajax({
                        url: '/fees/getFeesByClasses',
                        type: 'GET',
                        data: { selectedClass: selectedClass },
                        success: function (Fees) {
                            var selectedFees = $('#amount');
                            selectedFees.empty();
                            $('#amount').val(parseFloat(Fees.ClassFees));                        },
                        error: function (error) {
                            console.log("error:", error);
                         }
                     });
                   });

        $(document).ready(function(){
            var dateNow = new Date().toISOString().split('T')[0];
            $('#payment_date').attr('max', dateNow);

            $('#amount, #paid').on('input', function() {
                var x = parseFloat($('#amount').val());
                var y = parseFloat($('#paid').val()) ;

                var difference = x - y;
                $('#left_to_pay').val(difference);

                if (difference < 0) {
                    $('#paid').val(x);
                     difference = 0;
                     $('#left_to_pay').val(difference);

                 }


                $('#payment_status').empty();
                if(difference == 0){
                    var content = '<option id="Paid" value="Paid">PAID</option>';
                    $('#payment_status').append(content);
                }else{
                    var content = '<option id="Pending" value="Pending">PENDING</option>';
                    $('#payment_status').append(content);
                }

               });

            $('.add').click(function(){
                Clean()
                $('#formFieldID').modal({show:true, keyboard:false, backdrop:'static'});
            });

            $('.save').on('click', function(e){

                var form = document.getElementById('Field');
                if(form.checkValidity() === false){
                   e.stopPropagation();
                   form.classList.add('was-validated');
                }else{
                    Swal.fire({
                        title:' Enregistrement payement',
                        text: ' Do you want to save this payement?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                            var cmd = $('#cmd').val();
                            var student = $('#student').val();
                            var payment_date = $('#payment_date').val();
                            var payment_reference = $('#payment_reference').val();
                            var paid = $('#paid').val();
                            var left_to_pay = $('#left_to_pay').val();
                            var payment_status = $('#payment_status').val();
                            var payment_method = $('#payment_method').val();
                            var classe = $('#classe').val();
                            sweet();
                                $.ajax({
                                    'type':'POST',
                                    'url': '/fees/save',
                                    data:{
                                        cmd:parseInt(cmd),
                                        payment_reference:payment_reference,
                                        payment_date:payment_date,
                                        paid:paid,
                                        left_to_pay:left_to_pay,
                                        payment_status:payment_status,
                                        payment_method:payment_method,
                                        studentID:parseInt(student),
                                        classID:parseInt(classe),
                                        },
                                    dataType:'json',
                                    headers:{'X-CSRF-Token':csrfToken},
                                    success:function(data){
                                        if(data.error == false){
                                            Swal.fire({
                                                title:'Success',
                                                text:data.message,
                                                icon:'success'
                                            });
                                            // table.ajax.reload();
                                            Clean()
                                            location.reload();
                                            $('#formFieldID').modal('hide');
                                        }
                                    },

                                        error:function(error){
                                        if(error.status == 404){
                                            var response = JSON.parse(error.responseText);
                                            var listErrors = response.message;
                                            var listObject = Object.values(listErrors);
                                            var resTxt = '';
                                            listObject.forEach(element => {
                                            resTxt += " \n" + element.toString();
                                            });
                                            Swal.fire({
                                                    title:'Error',
                                                    text:resTxt,
                                                    icon:'error'
                                                });
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    });
                });
    </script>
@endsection
