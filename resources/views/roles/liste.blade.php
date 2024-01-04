@extends("layouts.admin")
@section("title",__("List Roles"))
@section("title_content",__("List Role"))
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
            <h4 class="text-white">{{__(" Roles list")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Code</th>
                    <th scope="col">Status</th>
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
          <h5 class="modal-title text-white">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="idFormROle" name="formRole" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="cmd" id="idCmd" value="0">
                <div class="form-group">
                  <label for="roleName">{{__(" Role")}}</label>
                  <input type="text" name="roleName" required class="form-control" id="roleName" aria-describedby="emailHelp" placeholder="{{__('Enter value')}}">

                </div>
                <div class="form-group">
                  <label for="roleCode">Code</label>
                  <input type="text" name="roleCode" required class="form-control" id="roleCode" placeholder="{{__('Enter value')}}">
                </div>
                <div class="form-group">
                    <label for="roleName">{{__(" Status")}}</label>
                    <select class="form-control" name="status" id="status">
                        <option value="Actif">{{__("Actif")}}</option>
                        <option value="Inactif">{{__("Inactif")}}</option>
                    </select>

                  </div>
              </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary save">{{__(" Save")}}</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            url:"{{url('roles/getRoles')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            {'data':'role'},
            {'data':'code'},
            {'data':'status'},
            {'data':'options'}
        ]
    });

    function edit(role){
        $('#roleName').val(role.Role);
        $('#roleCode').val(role.Code);
        $('#status').val(role.Status);
        $('#formRoleID').modal({show:true, keyboard:false, backdrop:'static'});
    }

        $(document).ready(function(){
            $('.add').click(function(){
                $('#formRoleID').modal({show:true, keyboard:false, backdrop:'static'});
            });

            $('.save').on('click', function(e){
                var form = document.getElementById('idFormROle');
                if(form.checkValidity() === false){
                   e.stopPropagation();
                   form.classList.add('was-validated');
                }else{

                    Swal.fire({
                        title:' Enregistrement role',
                        text: ' Are you whant to save this role?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                            var role = $('#roleName').val();
                            var code = $('#roleCode').val();
                            var status = $('#status').val();

                            $.ajax({
                                'type':'POST',
                                'url': '/roles/save',
                                data:{
                                    roleName:role,
                                    roleCode:code,
                                    status:status
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
                                        $('#formRoleID').modal('hide');
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
