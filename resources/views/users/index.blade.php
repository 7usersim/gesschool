@extends("layouts.admin")
@section("title",__("List user"))
@section("title_content",__("List user"))
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
            <h4 class="text-white">{{__(" User list")}}</h4>
        </div>
        <div class="card-body">

            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">Matricule</th>
                    <th scope="col">First name</th>
                    <th scope="col">Last name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Email</th>
                    <th scope="col">status</th>
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
<div class="modal" tabindex="-1" role="dialog" id="formUserID" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary ">
          <h5 class="modal-title text-white"> User manage</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="idFormUser" name="formUser" novalidate class="needs-validation">
                @csrf
                <input type="hidden" name="cmd" id="idCmd" value="0">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="role">{{__(" Role")}}</label>
                        <select name="role" id="role" class="form-control">

                            @foreach ($rolesList as $role)
                                <option value="{{$role->id}}">{{$role->role_name}}</option>
                            @endforeach
                        </select>

                      </div>
                      <div class="form-group col-md-4">
                        <label for="firstname"> First name</label>
                        <input type="text" name="firstname" required class="form-control" id="firstname" placeholder="{{__('Enter value')}}">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="lastname"> Last name</label>
                        <input type="text" name="lastname" required class="form-control" id="lastname" placeholder="{{__('Enter value')}}">
                      </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="country">{{__(" Country")}}</label>
                        <select name="country" id="country" class="form-control">
                            <option value="Cameroun"> Cameroun</option>
                            <option value="Congo"> Congo</option>
                            <option value="Gabon"> Gabon</option>
                            <option value="Tchad"> Tchad</option>
                        </select>

                      </div>
                      <div class="form-group col-md-4">
                        <label for="city"> City</label>
                        <input type="text" name="city" required class="form-control" id="city" placeholder="{{__('Enter value')}}">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="adress"> Address</label>
                        <input type="text" name="adress" required class="form-control" id="adress" placeholder="{{__('Enter value')}}">
                      </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="phone"> Phone</label>
                        <input type="text" name="phone" required class="form-control" id="phone" placeholder="{{__('Enter value')}}">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="email"> Email</label>
                        <input type="text" name="email" required class="form-control" id="email" placeholder="{{__('Enter value')}}">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="username"> Username</label>
                        <input type="text" name="username" required class="form-control" id="username" placeholder="{{__('Enter value')}}">
                      </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="password"> Password</label>
                        <input type="password" name="password" required class="form-control" id="password" placeholder="{{__('Enter value')}}">
                      </div>
                      <div class="form-group col-md-4">
                        <label for="confirm_password"> Confirm password</label>
                        <input type="password" name="confirm_password" required class="form-control" aria-describedby="passwordHelp" id="confirm_password" placeholder="{{__('Enter value')}}">
                        <small id="passwordHelp" class="form-text text-muted"></small>
                    </div>
                      <div class="form-group col-md-4">
                        <label for="roleName">{{__(" Status")}}</label>
                    <select class="form-control" name="status" id="status">
                        <option value="Actif">{{__("Actif")}}</option>
                        <option value="Inactif">{{__("Inactif")}}</option>
                    </select>
                      </div>
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
            url:"{{url('users/get_users')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            {'data':'matricule'},
            {'data':'firstname'},
            {'data':'lastname'},
            {'data':'role'},
            {'data':'phone'},
            {'data':'email'},
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
                $('#formUserID').modal({show:true, keyboard:false, backdrop:'static'});
            });

            $('.save').on('click', function(e){
                var form = document.getElementById('idFormUser');
                if(form.checkValidity() === false){
                   e.stopPropagation();
                   form.classList.add('was-validated');
                }else{

                    Swal.fire({
                        title:' Enregistrement user',
                        text: ' Are you whant to save this user?',
                        icon: 'info',
                        showCancelButton:true,
                        confirmButtonColor:'#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: ' Yes, save it !',
                        cancelButtonText: ' Cancel'
                    }).then((result) =>{
                        if(result.value === true){
                            var role = $('#role').val();
                            var cmd = $('#idCmd').val();
                            var first_name = $('#firstname').val();
                            var last_name = $('#lastname').val();
                            var country = $('#country').val();
                            var city = $('#city').val();
                            var adress = $('#adress').val();
                            var phone = $('#phone').val();
                            var email = $('#email').val();
                            var username = $('#username').val();
                            var password = $('#password').val();
                            var confirm_password = $('#confirm_password').val();
                            var status = $('#status').val();

                            if( password != confirm_password){
                                Swal.fire({
                                            title:'Error',
                                            text:" password and confirm is not match",
                                            icon:'error'
                                        });
                                $('#passwordHelp').html("password and confirm is not match").css("color","red");
                            }else{
                                    $.ajax({
                                    'type':'POST',
                                    'url': '/users/save',
                                    data:{
                                        roleID:parseInt(role),
                                        cmd:parseInt(cmd),
                                        first_name:first_name,
                                        last_name:last_name,
                                        country:country,
                                        city:city,
                                        adress:adress,
                                        phone:phone,
                                        email:email,
                                        username:username,
                                        password:password,
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


                        }
                    });


                }
            });
        });
    </script>
@endsection
