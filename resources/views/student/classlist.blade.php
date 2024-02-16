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
            <label for="cycle">{{__('Cycle') }}</label>
            <select name="cycle" id="cycle" class="form-control">
                <option value="" selected disabled>Select a cycle</option>
                @foreach ($cycleList as $cycle)
                    <option value="{{$cycle->id}}">{{$cycle->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="field">{{__('Field') }}</label>
            <select name="field" id="field" class="form-control">
                <option value="" selected disabled>Select a field</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="class">{{__('Class') }}</label>
            <select name="classe" id="classe" class="form-control">
                <option value="" selected disabled>Select a class</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="firstname">{{__('Firstname')  }}</label>
            <input type="text" name="firstname" id="firstname" class="form-control">
        </div>
    </div>
        <button class="btn btn-primary search" type="button" id="search">
           <i class="fa fa-search"></i>   Search
        </button>
     </form>

    <div class="row">
    </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <h4 class="header-title">Class List</h4>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead class=" bg-info text-white">
                                <tr>
                                    <th>Firstname</th>
                                    <th data-priority="1">Lastname</th>
                                    <th data-priority="3">Sexe</th>
                                    <th data-priority="1">Address</th>
                                    <th data-priority="3">Cycle</th>
                                    <th data-priority="3">Field</th>
                                    <th data-priority="6">Class</th>
                                    <th data-priority="6">Options</th>
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
        var cycle = $("#cycle").val();
        var filiere = $("#field").val();
        var classe = $("#classe").val();
        var firstname = $("#firstname").val();

        $.ajax({
            type: "POST",
            url: "/student/AllStudent",
            data: {
                cycle: cycle,
                filiere: filiere,
                classe: classe,
                firstname: firstname
            },
            dataType:'json',
            headers:{'X-CSRF-Token':csrfToken},
            success: function(results) {
            var resultTable = $("#tabcontent");
            resultTable.empty();
            // console.log(results);
               results.datas.forEach(data =>{
               content = `<tr>
                <td>${data.firstname}</td>
                <td>${data.lastname}</td>
                <td>${data.sexe}</td>
                <td>${data.address}</td>
                <td>${data.cycle}</td>
                <td>${data.field}</td>
                <td>${data.class}</td>
                <td>${data.options}</td>
                </tr>`;
                resultTable.append(content);
                  });
               }
            });
         });


        $.ajax({
            url:'/student/getClassByField',
            type:'GET',
            success:function(optionC){
                var selectedClass = $('#classe');
                selectedClass.empty();
                // $('#firstname').empty();
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
                                // $('#firstname').empty();
                                $('#classe').append(`<option value="" selected disabled>Select a class</option>`);
                                optionB.fieldList.forEach(data =>{
                                    content = `<option value='${data.id}'>${data.nom}</option>`;
                                    selectedField.append(content);
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
                            $('#firstname').empty();
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
         });
</script>

@endsection


