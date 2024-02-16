@extends("layouts.admin")
@section("title",__("List students"))
@section("title_content",__("List students"))
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
    <a href="#" class="btn btn-sm btn-primary add"> <i class="fa fa-plus"></i> Add</a>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4 class="text-white">{{__(" Students list")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">Matricule</th>
                    <th scope="col">Firstname</th>
                    <th scope="col">Lastname</th>
                    <th scope="col">Cycle</th>
                    <th scope="col">Classe</th>
                    <th scope="col">Field</th>
                    <th scope="col">sexe</th>
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
          <h5 class="modal-title text-white"> Students manage</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="StudentForm" name="StudentForm" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="cmd" id="cmd" value="0">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="firstname">{{__("Firstname") }}</label>
                        <input type="text" name="firstname" id="firstname" required class="form-control" placeholder="{{__("enter the firstname") }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="lastname">{{__("Lastname") }}</label>
                        <input type="text" name="lastname" id="lastname" required class="form-control"  placeholder="{{__("enter the firstname") }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="address">{{__("Address") }}</label>
                        <input type="text" name="address" id="address" required class="form-control"  placeholder="{{__("enter the adress") }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="cycle">{{__("Cycle")  }}</label>
                        <select name="cycle" id="cycle" class="form-control">
                            <option value="" selected disabled>Select a cycle</option>
                            @foreach ($cycleList as $cycle )
                            <option value="{{ $cycle->id }}">{{ $cycle->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="field">{{__("Field")  }}</label>
                        <select name="field" id="field" class="form-control">
                            <option value="" selected disabled>Select a field</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="classe">{{__("Classe")  }}</label>
                        <select name="classe" id="classe" class="form-control">
                            <option value="" selected disabled>Select a class</option>
                        </select>
                    </div>

                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="email">{{__("email") }}</label>
                        <input type="email" name="email" id="email" required class="form-control"  placeholder="{{__("enter the email") }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="annee">{{__("Date of birth") }}</label>
                        <input type="date" name="date_birth" id="date_birth" required class="form-control" placeholder="{{__("enter the year") }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="Parent's name">{{__("Parent's name") }}</label>
                        <input type="text" name="parent_name" id="parent_name" required class="form-control" placeholder="{{__("Parent's name") }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="sexe">{{ __("Sexe") }}</label>
                        <select name="sexe" id="sexe" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
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
            url:"{{url('/student/getStudent')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
        {'data' :'matricule'},
        {'data' :'firstname'},
        {'data' :'lastname'},
        {'data' :'cycle'},
        {'data' :'classe'},
        {'data' :'field'},
        {'data' :'sexe'},
        {'data' :'options'}
        ]
    });

    function cleanInput(){
        // console.log(student);
        $('#cmd').val('');
        $('#firstname').val('');
        $('#lastname').val('');
        $('#date_birth').val('');
        $('#parent_name').val('');
        $('#email').val('');
        $('#sexe').val('');
        $('#address').val('');
        $('sexe').val('');
        $('#cycle').val('');
        $('#classe').val('');
        $('field').val('');
    }

    function edit(student){
        console.log(student.NameClass);
        $('#cmd').val(student.id);
        $('#firstname').val(student.FirstName);
        $('#lastname').val(student.LastName);
        $('#date_birth').val(student.DateBirth);
        $('#parent_name').val(student.ParentName);
        $('#email').val(student.Email);
        $('#sexe').val(student.Sexe);
        $('#address').val(student.Address);
        $('sexe').val(student.Sexe);
        $('#cycle').val(student.IDCycle);
        $('#classe').val(student.IDClass);
        $('field').val(student.IDField);
        $('#formFieldID').modal({show:true, keyboard:false, backdrop:'static'});

    }

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


        $(document).ready(function(){

            $("#cycle").on('change', function(){
                        var selectedCycle = $(this).val();
                        $.ajax({
                            url:'/student/getFiliereByCycle',
                            type:'GET',
                            data:{optionA: selectedCycle},
                            success:function(optionB){
                                var selectedField = $("#field");
                                selectedField.empty();
                                selectedField.append(`<option value="" selected disabled>Select a field</option>`);
                                $('#classe').empty();
                                $('#student').empty();
                                $('#classe').append(`<option value="" selected disabled>Select a class</option>`);
                                optionB.fieldList.forEach(data =>{
                                    content = `<option value='${data.id}'>${data.nom}</option>`;
                                    $('#field').append(content);
                                });
                            },
                            error:function(error){
                                console.log("error:",error);
                            }
                        });
                    });


                $("#field").on('change',function(){
                    var selectedField = $(this).val();
                    $.ajax({
                        url:'/student/getClassByField',
                        type:'GET',
                        data:{selectedField:selectedField},
                        success:function(optionC){
                            var selectedClass = $('#classe');
                            selectedClass.empty();
                            selectedClass.append(`<option value="" selected disabled>Select a class</option>`);
                            optionC.classesList.forEach(data =>{
                                content =`<option value='${data.id}'>${data.name}</option>`;
                                $('#classe').append(content);
                            })
                        },
                        error:function(error){
                            console.log("error:",error);
                        }
                    });
                });


            $('.add').click(function(){
                cleanInput();
                $('#formFieldID').modal({show:true, keyboard:false, backdrop:'static'});
            });

            $('.save').on('click', function(e){
                var form = document.getElementById('StudentForm');
                if(form.checkValidity() === false){
                   e.stopPropagation();
                   form.classList.add('was-validated');
                }else{
                    Swal.fire({
                        title:' Enregistrement eleve',
                        text: ' Do you want to save this student ?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                    var cmd = $('#cmd').val();
                    var firstname = $("#firstname").val();
                    var lastname = $("#lastname").val();
                    var sexe = $("#sexe").val();
                    var cycle = $("#cycle").val();
                    var field = $("#field").val();
                    var classe = $("#classe").val();
                    var date_birth = $("#date_birth").val();
                    var email = $("#email").val();
                    var parent_name = $("#parent_name").val();
                    var address = $("#address").val();

                                $.ajax({
                                    'type':'POST',
                                    'url': '/student/save',
                                    data:{
                                        cmd:parseInt(cmd),
                                        firstname:firstname,
                                        lastname:lastname,
                                        sexe:sexe,
                                        CycleID:parseInt(cycle),
                                        FieldID:parseInt(field),
                                        ClassID:parseInt(classe),
                                        date_birth:date_birth,
                                        email:email,
                                        parent_name:parent_name,
                                        address:address,
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
                                            table.ajax.reload();
                                            cleanInput();
                                            $('#formFieldID').modal('hide');
                                        }   var confirm_password = $('#confirm_password').val('');
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
