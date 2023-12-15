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
    <div class="card">
        <div class="card-header bg-success text-white">
            <h4>{{__(" Roles list")}}</h4>
        </div>
        <div class="card-body">
            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Code</th>
                    <th scope="col">Status</th>
                    <th scope="col">Options</th>
                  </tr>
                </thead>
                <tbody>
                    <?php $count =0 ?>
                    @foreach ($listes as $role)
                    <tr>
                        <th scope="row">{{++$count}}</th>
                        <td>{{$role->role_name}}</td>
                        <td>{{$role->code_role}}</td>
                        <td>{{$role->status_role}}</td>
                        <td>
                            <a href="{{url('roles/edit/'.$role->id)}}" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> Edit</a>
                        </td>

                      </tr>
                    @endforeach

                </tbody>
              </table>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<!-- Required datatable js -->
<script src="{{asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

   <!-- Datatable init js -->
   <script src="{{asset('js/pages/datatables.init.js')}}"></script>
@endsection
