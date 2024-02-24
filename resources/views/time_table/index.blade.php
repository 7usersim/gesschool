@extends("layouts.admin")
@section("title",__("Time table"))
@section("title_content",__("Time table"))
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
            <h4 class="text-white">{{__(" Time table")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    {{-- <th scope="col">Class</th> --}}
                    <th scope="col">Date </th>
                    {{-- <th scope="col">Courses</th> --}}
                    <th scope="col">Begin</th>
                    <th scope="col">End</th>
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
          <h5 class="modal-title text-white">Time table</h5>
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
                            <label for="class">{{__('Class')}}</label>
                            <select name="classe" id="classe" class="form-control" >
                                <option value="" disabled selected>{{__('Select class')}}</option>
                                @foreach ($classList as $classe )
                                    <option value="{{ $classe->id }}">{{$classe->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="course">{{__('Course')}}</label>
                            <select name="course" id="course" class="form-control" >
                                <option value="" disabled selected>{{__('Select course')}}</option>
                            </select>
                         </div>
                     </div>
                     <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="date">{{__('date')}}</label>
                             <input type="date" id="date" name="date" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="starting_hour">{{__('Starting hour')}}</label>
                             <input type="time" id="starting_hour" name="starting_hour" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="closing_hour">{{__('Closing hour')}}</label>
                             <input type="time" id="closing_hour" name="closing_hour" class="form-control">
                        </div>
                   </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-primary save">{{__(" Save")}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                 </div>
              </form>
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
            url:"{{url('/time/getList')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            // {'data':'classe'},
            {'data':'date'},
            // {'data':'course'},
            {'data':'starting_hour'},
            {'data':'closing_hour'},
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
        $('#classe').val('');
        $('#course').val('');
        $('#starting_hour').val('');
        $('#closing_hour').val('');
        $('#date').val('');
        $('#cmd').val('');
    }



    // function edit(note){
    //     console.log(note);
    //     $('#cmd').val(note.id);
    //     $('#course').val(note.CoursesId);
    //     $('#classe').val(note.IdClass);
    //     $('#note').val(note.Note);
    //     $('#student').val(note.StudentID);
    //     $('#exam').val(note.IdExam);
    //     $('#formRoleID').modal({show:true, keyboard:false, backdrop:'static'});
    // }



                $("#classe").on('change',function(){
                    var selectedClass = $(this).val();
                    $.ajax({
                        url:'/note/getCoursesByClass',
                        type:'GET',
                        data:{selectedClass:selectedClass},
                        success:function(SelectedC){
                            console.log(SelectedC.result);
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
                        title:' Enregistrement d\'emploi du temps',
                        text: ' Do you want to save this time table ?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                            var course = $('#course').val();
                            var date = $('#date').val();
                            var classe = $('#classe').val();
                            var starting_hour = $('#starting_hour').val();
                            var closing_hour = $('#closing_hour').val();
                            var cmd = $('#cmd').val();
                            sweet();
                            $.ajax({
                                'type':'POST',
                                'url': '/time/save',
                                data:{
                                    date:date,
                                    starting_hour:starting_hour,
                                    closing_hour:closing_hour,
                                    CourseID:parseInt(course),
                                    ClassID:parseInt(classe),
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
