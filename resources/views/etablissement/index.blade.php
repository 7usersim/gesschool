@extends('layouts.admin')

@section('title', __("Add school | GESSCHOOL "))
@section('title_content', __("Add school | GESSCHOOL "))

@section('css')
  <!-- DataTables -->
  <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

  <!-- Responsive datatable examples -->
  <link href="{{asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        @include('flash-message')
        <a href="#" class="btn btn-sm btn-primary add"> <i class="fa fa-plus"></i> Add</a>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="text-white">{{__("  list")}}</h4>
            </div>
            <div class="card-body">

                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                      <tr>
                        <th scope="col">Nom</th>
                        <th scope="col">Type</th>
                        <th scope="col">Ville</th>
                        <th scope="col">Pays</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Operations</th>
                      </tr>
                    </thead>
                    <tbody>

                    </tbody>
                  </table>
            </div>
        </div>
    </div>

<!-- Initialisation du modal -->
<div class="modal" tabindex="-1" role="dialog"  data-backdrp='static' id="formRoleId" data-keyboard='false'>
  <div class="modal-dialog modal-dialog-center modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary ">
        <h5 class="modal-title text-white">{{__("Add School")}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="formRole" id="formRole" method="POST" class="needs_validation" enctype="multipart/form-data" novalidate>
         @csrf
         <input type="hidden" name="cmd"  id="cmd" value="0" />
         <div class="row">
             <div class="col-md-4 mb-3">
                 <label for="validationCustom01">Name</label>
                 <input type="text" class="form-control @error('name') is-invalid @enderror"  value="{{old('name')}}" name="name" id="name" placeholder="Please enter the name"   required>

             </div>
             <div class="col-md-4 mb-3">
                 <label for="validationCustom02">Address</label>
                 <input type="text" class="form-control @error('adresse') is-invalid @enderror"  value="{{old('adresse')}}" name="adresse"  id="adresse" placeholder="Please enter the school address"  required/>

             </div>
             <div class="col-md-4 mb-3">
                 <label for="validationCustomUsername">code postal</label>
                 <div class="input-group">

                     <input type="text" class="form-control @error('code_postal') is-invalid @enderror"  value="{{old('code_postal')}}" name="code_postal"  id="code_postal" placeholder="Postal code" aria-describedby="inputGroupPrepend" required>

                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-md-4 mb-3">
                 <label for="validationCustom01">City</label>
                 <input type="text"class="form-control @error('ville') is-invalid @enderror"  value="{{old('ville')}}" id="ville" name="ville"   placeholder="please the city"   required>

             </div>
             <div class="col-md-4 mb-3">
                 <label for="validationCustom02">Country</label>
                 <input type="text" class="form-control @error('pays') is-invalid @enderror" id="pays" name="pays" value="{{old('pays')}}"   placeholder="Country"  required>

             </div>
             <div class="col-md-4 mb-3">
                 <label for="validationCustomUsername">Email</label>
                 <div class="input-group">
                     <div class="input-group-prepend">
                     <span class="input-group-text" id="inputGroupPrepend">@</span>
                     </div>
                     <input type="email" class="form-control " name="email"  value="{{old('email')}}"  id="email" placeholder="Email" aria-describedby="inputGroupPrepend" required>

                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-md-4 mb-3">
                 <label for="phone">Phone number</label>
                 <input type="tel" class="form-control"   value="{{old('telephone')}}" name="telephone" id="telephone" placeholder="Pleaase the phone number"   required>

             </div>
             <div class="col-md-4 mb-3">
                 <label for="fax">Fax</label>
                 <input type="text" class="form-control  " name="fax"  value="{{old('fax')}}" id="fax" placeholder="fax"  required>

             </div>
             <div class="col-md-4 mb-3">
                 <label for="validationCustomUsername">Web site</label>
                 <div class="input-group">
                     <div class="input-group-prepend">
                     <span class="input-group-text" id="web_site">Https</span>
                     </div>
                     <input type="text" class="form-control " name="site_web" id="site_web"  value="{{old('site_web')}}" placeholder="web site" aria-describedby="inputGroupPrepend" required>

                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-md-6 mb-3">
                 <label for="validationCustom03">Creation date</label>
                 <input type="date" class="form-control " name="date_fondation"  value="{{old('date_fondation')}}" id="date_fondation" placeholder="Phone number" required>

             </div>
             <div class="col-md-3 mb-3">
                 <label>Type</label>
                 <select class="custom-select " name="type_ets" id="type_ets" value="{{ old('type_ets') }}" required>
                     <option value="general">General</option>
                     <option value="public">Technique</option>

                 </select>
                 <div class="invalid-feedback">Example invalid custom select feedback</div>
             </div>
             <div class="col-md-3 mb-3">
                 <label>logo</label>
              <input type="text" class="form-control "  name="logo"  value="{{old('logo')}}" id="logo" placeholder="" required>

                 <div class="invalid-feedback">Example invalid custom select feedback</div>
             </div>


             {{-- <div class="col-md-3 mb-3">
                 <label>logo</label>


                 <div class="custom-file">
                     <input type="file" class="custom-file-input " value="{{old('logo')  }}" name="logo" id="logo" required>
                     <label class="custom-file-label" for="validationCustomFile">Choose file...</label>
                     <div class="invalid-feedback">
                     Example invalid custom file feedback
                     </div>
                 </div>
             </div> --}}
         </div>

         {{-- @if($items = true) --}}
             {{-- <button class="btn btn-primary disabled" type="submit" disabled>Submit </button> --}}
          {{-- @else --}}
             {{-- <button class="btn btn-primary" name="save" id="save" type="submit" >Submit </button> --}}
          {{-- @endif --}}
     </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save">{{__("save")}}</button>
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
        lengthMenu:[[50,100,500,-1], [50,100,500,'All']],
        processing:true,
        locale:"{{config('app.locale')}}",
        serverSide:true,
        ajax:{
            url:"{{url('/etablissement/getList')}}",
            dataType:'json',
            type:'POST',
            data:{_token:csrfToken}
        },
        columns:[
            {data:'name'},
            {data:'type_ets'},
            {data:'ville'},
            {data:'pays'},
            {data:'telephone'},
            {data:'options'}


        ]
     });

     function edit(config){
        // console.log(role);
      // console.log(role);
      $('#name').val(config.name);
      $('#cmd').val(config.id);
      $('#adresse').val(config.adresse);
      $('#code_postal').val(config.code_postal);
      $('#ville').val(config.ville);
      $('#pays').val(config.pays);
      $('#fax').val(config.fax);
      $('#telephone').val(config.telephone);
      $('#email').val(config.email);
      $('#site_web').val(config.site_web);
      $('#date_fondation').val(config.date_fondation);
      $('#type_ets').val(config.type_ets);
      $('#logo').val(config.logo);
      $('#formRoleId').modal({
        show:true,
        keyboard:false,
        backdrop:'static',
      })
    }


     $(document).ready(function(){
        $('#add').click(function(){
      $('#name').val('');
      $('#cmd').val('');
      $('#adresse').val('');
      $('#code_postal').val('');
      $('#ville').val('');
      $('#pays').val('');
      $('#fax').val('');
      $('#telephone').val('');
      $('#email').val('');
      $('#site_web').val('');
      $('#date_fondation').val('');
      $('#type_ets').val('');
      $('#logo').val('');
        })
     })

     $(document).ready(function(){
        $('#add').click(function(){
            $('#formRoleId').modal({
                show:true,
                keyboard:false,
                backdrop:'static',

            })
        })
        $('.save').on('click',function(e){
            var form = document.getElementById('formRole');
            if(form.checkValidity() == false){
                form.classList.add('was-validated')
            }else{
                e.stopPropagation();
                 var formData = new FormData($('#formRole')[0]);
                swal.fire({
                    title:'enregistrement Etablissement',
                    text:'voulez vous vraiment enregistrer cet etablissement ?',
                    icon:'info',
                    showCancelButton:true,
                    confirmButtonColor:'#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText:'Yes, save it !',
                    cancelButtonText:'Cancel'
                }).then((result) =>{
                    if(result.value === true){
                        var name = $('#name').val();
                        var adresse =$('#adresse').val();
                        var code_postal = $('#code_postal').val();
                        var ville = $('#ville').val();
                        var pays = $('#pays').val();
                        var fax = $('#fax').val();
                        var telephone = $('#telephone').val();
                        var email = $('#email').val();
                        var site_web = $('#site_web').val();
                        var date_fondation = $('#date_fondation').val();
                        var type_ets = $('#type_ets').val();
                        var logo = $('#logo').val();
                        var cmd = $('#cmd').val();

                        // console.log(cmd);

                // upload file
            //     $.ajax({
            //     type: 'POST',
            //     url: '/config/save',
            //     data: formData,
            //     dataType: 'json',
            //     processData: false,
            //     contentType: false,
            //     headers: {
            //         'X-CSRF-TOKEN': csrfToken,
            //     },

            //     error:function(error){
            //         if(error.status == 404){
            //             var response = JSON.parse(error.responseText);
            //             var listErrors = response.message;
            //             var listObject = Object.values(listErrors);
            //             var resTxt = '';
            //             listObject.forEach(element =>{
            //                 resTxt += '\n' +element.toString();
            //             });

            //             Swal.fire({
            //                 title: 'Error',
            //                 text: resTxt,
            //                 icon: 'error',
            //             });

            //             $('#formRole').modal('hide');
            //         }
            //     }
            // });



                        $.ajax({
                            'type':'POST',
                            'url':'/etablissement/save',

                            data:{
                                cmd:cmd,
                                name:name,
                                adresse:adresse,
                                code_postal:code_postal,
                                ville:ville,
                                pays:pays,
                                fax:fax,
                                telephone:telephone,
                                email:email,
                                site_web:site_web,
                                date_fondation:date_fondation,
                                type_ets:type_ets,
                                logo:logo
                            },
                            dataType: 'json',
                            headers:{
                                'X-CSRF-TOKEN':csrfToken,
                            },
                            success:function(data){
                                // table.ajax.reload();

                                // console.log(data);
                                if(data.error == false){
                                Swal.fire({
                                    title:'Success',
                                    text:data.message,
                                    icon:'success',
                                });

                                $('#formRoleId').modal('hide')
                                };

                                },
                            error:function(error){
                                if(error.status == 404){
                                    var response =JSON.parse(error.responseText);
                                    var listErrors = response.message;
                                    var listObject = Object.values(listErrors);
                                    var resTxt = '';
                            listObject.forEach(element =>{
                                resTxt += '\n' +element.toString();

                            });

                        Swal.fire({
                            title:'Error',
                            text:resTxt,
                            icon:'error',
                        });

                    $('#formRole').modal('hide')
                            }
                         }
                        })

                    }
                })
            }
        })
     })
 </script>
@endsection
