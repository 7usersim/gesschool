@extends("layouts.admin")
@section("title",__("List field"))
@section("title_content",__("List field"))
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
            <h4 class="text-white">{{__(" Field list")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Description</th>
                    <th scope="col">Cycle</th>
                    <th scope="col">Responsible</th>
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
          <h5 class="modal-title text-white"> Field manage</h5>
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
                        <label for="cycle">{{__(" Cycle")}}</label>
                        <select name="cycle" id="cycle" class="form-control">
                            <option value="" selected disabled >{{__('Select cycle')}}</option>
                            @foreach ($cycleList as $cycle)
                                <option value="{{$cycle->id}}">{{$cycle->name}}</option>
                            @endforeach
                        </select>

                      </div>

                    <div class="form-group col-md-4">
                        <label for="school">{{__(" School")}}</label>
                        <select name="school" id="school" class="form-control">
                            <option value="" selected disabled >{{__('Select school')}}</option>
                            @foreach ($SchoolLists as $school)
                                <option value="{{$school->id}}">{{$school->name}}</option>
                            @endforeach
                        </select>

                     </div>

                      <div class="form-group col-md-4">
                        <label for="code"> Code</label>
                        <input type="text" name="code" required class="form-control" id="code" placeholder="{{__('Enter value')}}">
                      </div>
                    </div>

                     <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="name">Name</label>
                                <input type="text" name="nom" required class="form-control" id="nom" placeholder="{{__('Enter value')}}">
                              </div>

                                <div class="form-group col-md-4">
                                    <label for="responsible">{{__(" Responsable")}}</label>
                                    <select name="responsible" id="responsible" class="form-control">
                                        <option value="" selected disabled >{{__('Select responsible')}}</option>
                                        @foreach ($responsibleLists as $responsible)
                                            <option value="{{$responsible->id}}">{{$responsible->first_name}}</option>
                                        @endforeach
                                    </select>

                                 </div>

                                    <div class="form-group col-md-4 ">
                                        <label for="description"> Description</label>
                                        <input type="text" name="description" required class="form-control" id="description" placeholder="{{__('Enter value')}}">
                                    </div>

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
            url:"{{url('/filiere/getFiliere')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            {'data':'nom'},
            // {'data':'code'},
            {'data':'description'},
            {'data':'cycle'},
            // {'data':'school'},
            {'data':'responsible'},
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
                    // Swal.getContent().querySelector("strong").textContent = Swal.getTimerLeft();
                }, 10000)

            },
            onClose: function() {
                clearInterval(t)
            }
        }).then(function(t) {
            t.dismiss === Swal.DismissReason.timer
        });

         }

    // cleanInput();
    function cleanInput(){
        $('#cmd').val('');
        $('#cycle').val('');
        $('#school').val('');
        $('#responsible').val('');
        $('#description').val('');
        $('#nom').val('');
        $('#code').val('');
        // console.log(filiere);
        $('#Field').removeClass('was-validated');

         }

    function edit(filiere){
        // console.log(filiere);

        $('#cmd').val(filiere.id);
        $('#cycle').val(filiere.IDCycle);
        $('#school').val(filiere.IDSchool);
        $('#responsible').val(filiere.IDUser);
        $('#description').val(filiere.DescriptionFiliere);
        $('#nom').val(filiere.NameFiliere);
        $('#code').val(filiere.CodeFiliere);
        // console.log(filiere);
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
                        title:' Enregistrement filiere',
                        text: ' Do you want to save this field?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                            var cycle = $('#cycle').val();
                            var school = $('#school').val();
                            var responsible = $('#responsible').val();
                            var cmd = $('#cmd').val();
                            var description = $('#description').val();
                            var nom = $('#nom').val();
                            var code = $('#code').val();
                            // cleanInput();

                            sweet();
                                $.ajax({
                                    'type':'POST',
                                    'url': '/filiere/save',
                                    data:{
                                        cmd:parseInt(cmd),
                                        nom:nom,
                                        code:code,
                                        description:description,
                                        cycleID:parseInt(cycle),
                                        schoolID:parseInt(school),
                                        responsibleID:parseInt(responsible),
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
