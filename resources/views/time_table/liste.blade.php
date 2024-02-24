@extends("layouts.admin")
@section("title",__("List students"))
@section("title_content",__(""))
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
    <form method="POST" id="StudentForm" name="StudentForm" novalidate class="needs-validation ">
       @csrf
       <div class="form-row">
        <div class="form-group col-md-3">
            <label for="Classe">{{__('Classe') }}</label>
            <select name="classe" id="classe" class="form-control">
                <option value="" selected disabled>Select classe</option>
                @foreach ($classList as $class)
                    <option value="{{$class->id}}">{{$class->name}}</option>
                @endforeach
            </select>
        </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <button class="btn btn-primary search" type="button" id="search" >
                   <i class="fa fa-search"></i>   Search
                </button>
        </div>
    </div>

     </form>

    <div class="row">
    </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <h4 class="header-title">Time Table</h4>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead class=" bg-info text-white">
                                <tr>
                                    <th data-priority="3">Date</th>
                                    <th>Class</th>
                                    <th data-priority="1">Course</th>
                                    <th data-priority="3">Start</th>
                                    <th data-priority="1">End</th>
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
@endsection

@section('js')
<!-- Required datatable js -->
<script src="{{asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

<script type="text/javascript">

    $(document).ready(function(){

        $("#search").on('click',function(event) {

        event.preventDefault();
        var classe = $("#classe").val();

        $.ajax({
            type: "POST",
            url: "/time/AllTimeList",
            data: {
                classe: classe
            },
            dataType:'json',
            headers:{'X-CSRF-Token':csrfToken},
            // success: function(results) {
            // var resultTable = $("#tabcontent");
            // resultTable.empty();
            // // console.log(results);
            //    results.datas.forEach(data =>{
            //    content = `<tr>
            //     <td>${data.firstname}</td>
            //     <td>${data.lastname}</td>
            //     <td>${data.sexe}</td>
            //     </tr>`;
            //     resultTable.append(content);
            //       });
            //    }
            });
         });




         });
</script>

@endsection


