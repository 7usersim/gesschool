@extends("layouts.admin")
@section("title",__("List Note"))
@section("title_content",__("List note"))
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
            <h4 class="text-white">{{__(" Note list")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">Note</th>
                    <th scope="col">Evaluation</th>
                    {{-- <th scope="col">Classe</th> --}}
                    <th scope="col">Courses</th>
                    <th scope="col">Student </th>
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
<div class="modal" tabindex="-1" role="dialog" id="formRoleID" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary ">
          <h5 class="modal-title text-white">Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="idFormROle" name="formRole" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="cmd" id="cmd" value="0">
                <div class="form-row">
                    <div class="form-group col-md-6">
                       <label for="exam">{{__('Exam')}}</label>
                       <select name="exam" id="exam" class="form-control" >
                            <option value="" disabled selected>{{__('Select exam')}}</option>
                            @foreach ($ExamList as $Exam )
                                <option value="{{ $Exam->id }}">{{$Exam->name}}</option>
                            @endforeach
                       </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="class">{{__('Class')}}</label>
                        <select name="classe" id="classe" class="form-control" >
                             <option value="" disabled selected>{{__('Select class')}}</option>
                             @foreach ($classList as $classe )
                                 <option value="{{ $classe->id }}">{{$classe->name}}</option>
                             @endforeach
                        </select>
                     </div>
                    </div>
                <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="course">{{__('Course')}}</label>
                            <select name="course" id="course" class="form-control" >
                                 <option value="" disabled selected>{{__('Select course')}}</option>
                            </select>
                 </div>
                <div class="form-group col-md-6">
                       <label for="student">{{__('Student')}}</label>
                       <select name="student" id="student" class="form-control" >
                            <option value="" disabled selected>{{__('Select student')}}</option>
                       </select>
                    </div>
                 </div>
                 <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="note">{{__("Note")}}</label>
                        <input type="text" id="note" name="note" class="form-control">
                    </div>
                 </div>
                 </div>
              </form>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary save">{{__(" Save")}}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            url:"{{url('/notes/getList')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            {'data':'note'},
            {'data':'evaluation'},
            // {'data':'class'},
            {'data':'course'},
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

    function CleanInput()
    {
        $('#note').val('');
        $('#course').val('');
        $('#student').val('');
        $('#classe').val('');
        $('#exam').val('');
        $('#cmd').val('');
    }

    function edit(cmd){
    $.ajax({
        url: '/notes/getNoteDetails',
        type: 'GET',
        data: { cmd: cmd },
        success: function(note) {
            console.log(note);
            $('#cmd').val(note.noteId);
            $('#course').val(note.CoursesId);
            $('#classe').val(note.IdClass);
            $('#note').val(note.Note);
            $('#student').val(note.StudentID);
            $('#exam').val(note.IdExam);
            $('#formRoleID').modal({show:true, keyboard:false, backdrop:'static'});
        },
        error: function(error) {
            Swal.fire({
                title:'Error',
                text:'Error during update',
                icon:'error'
            });
        }
    });
}


    function edit(note){
        console.log(note);
        $('#cmd').val(note.id);
        $('#course').val(note.CoursesId);
        $('#classe').val(note.IdClass);
        $('#note').val(note.Note);
        $('#student').val(note.StudentID);
        $('#exam').val(note.IdExam);
        $('#formRoleID').modal({show:true, keyboard:false, backdrop:'static'});
    }


    $("#classe").on('change',function(){
                    var selectedClass = $(this).val();
                    $.ajax({
                        url:'/evaluation/getStudent',
                        type:'GET',
                        data:{selectedClass:selectedClass},
                        success:function(optionC){
                            var selectedStudent = $('#student');
                            selectedStudent.empty();
                            selectedStudent.append(`<option value="" selected disabled>Select a student</option>`);
                            optionC.studentList.forEach(data =>{
                                content =`<option value='${data.id}'>${data.lastname} ${data.firstname}   </option>`;
                                $('#student').append(content);
                            })
                        },
                        error:function(error){
                            console.log("error:",error);
                        }
                    });
                });

                $("#classe").on('change',function(){
                    var selectedClass = $(this).val();
                    $.ajax({
                        url:'/note/getCoursesByClass',
                        type:'GET',
                        data:{selectedClass:selectedClass},
                        success:function(SelectedC){
                            var selectedCourses = $('#course');
                            selectedCourses.empty();
                            selectedCourses.append(`<option value="" selected disabled>Select course</option>`);
                            SelectedC.result.forEach(data =>{
                                content =`<option value='${data.id}'>${data.name}</option>`;
                                $('#course').append(content);
                            })
                        },
                        error:function(error){
                            console.log("error:",error);
                        }
                    });
                });





        $(document).ready(function(){
            $('.add').click(function(){
                CleanInput();
                $('#formRoleID').modal({show:true, keyboard:false, backdrop:'static'});
            });

            $('.save').on('click', function(e){
                var form = document.getElementById('idFormROle');
                if(form.checkValidity() === false){
                   e.stopPropagation();
                   form.classList.add('was-validated');
                }else{

                    Swal.fire({
                        title:' Enregistrement d\'evaluation',
                        text: ' Do you want to save this note  ?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                            var course = $('#course').val();
                            var exam = $('#exam').val();
                            var student = $('#student').val();
                            var note = $('#note').val();
                            var cmd = $('#cmd').val();
                            sweet();
                            $.ajax({
                                'type':'POST',
                                'url': '/notes/save',
                                data:{
                                    note:note,
                                    CourseID:parseInt(course),
                                    StudentID:parseInt(student),
                                    EvaluationID:parseInt(exam),
                                    cmd:cmd,
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
                                        // CleanInput();
                                        // $('#formRoleID').modal('hide');
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
