@extends("layouts.admin")
@section("title",__("List courses"))
@section("title_content",__("List courses"))
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
            <h4 class="text-white">{{__(" List courses")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">code</th>
                    <th scope="col">Description</th>
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
          <h5 class="modal-title text-white"> Courses manage</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="Field" name="Field" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="cmd" id="cmd" value="0">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="name">Name</label>
                        <input type="text" name="name" required class="form-control" id="name" placeholder="{{__('Enter value')}}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="code "> Code</label>
                        <input type="text" name="code" required class="form-control" id="code" placeholder="{{__('Enter value')}}">
                      </div>
                    <div class="form-group col-md-6">
                        <label for="description "> description</label>
                        <input type="text" name="desciption" required class="form-control" id="description" placeholder="{{__('Enter value')}}">
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
            url:"{{url('/courses/getCourses')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            {'data':'name'},
            {'data':'code'},
            {'data':'description'},
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

    function cleanInput(){
        $('#name').val('');
        $('#code').val('');
        $('#description').val('');
        $('#cmd').val('');

         }

    function edit(courses){
        $('#cmd').val(courses.id);
        $('#name').val(courses.NameCourse);
        $('#code').val(courses.Code);
        $('#description').val(courses.Description);
        $('#formFieldID').modal({show:true, keyboard:false, backdrop:'static'});
    }

        $(document).ready(function(){
            $('.add').click(function(){
                cleanInput();
                $('#formFieldID').modal({show:true, keyboard:false, backdrop:'static'});
            });

            $('.save').on('click', function(e){

                var form = document.getElementById('Field');
                if(form.checkValidity() === false){
                   e.stopPropagation();
                   form.classList.add('was-validated');
                }else{
                    Swal.fire({
                        title:' Enregistrement Classe',
                        text: ' Do you want to save this class?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                            var name = $('#name').val();
                            var code = $('#code').val();
                            var cmd = $('#cmd').val();
                            var description = $('#description').val();
                            sweet();
                                $.ajax({
                                    'type':'POST',
                                    'url': '/courses/save',
                                    data:{
                                        cmd:parseInt(cmd),
                                        name:name,
                                        code:code,
                                        description:description,
                                        },
                                    dataType:'json',
                                    headers:{'X-CSRF-Token':csrfToken},
                                    success:function(data){

                                        if(data.error == false){
                                            Swal.fire({
                                                title:'success',
                                                text:data.message,
                                                icon:'success'
                                            });
                                            table.ajax.reload();
                                            cleanInput();
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
