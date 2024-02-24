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
<div class="row" >
<div class="col-md-12">
    @include('flash-message')
    <form method="POST" id="StudentForm" name="StudentForm" novalidate class="needs-validation ">
       @csrf
       <div class="form-row">
        <div class="form-group col-md-4">
            <label for="cycle">{{__('Classe') }}</label>
            <select name="classe" id="classe" class="form-control">
                <option value="" selected disabled>Select a class</option>
                @foreach ($classList as $classe)
                    <option value="{{$classe->id}}">{{$classe->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="course">{{__('Course') }}</label>
            <select name="course" id="course" class="form-control">
                <option value="" selected disabled>Select a course</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="Exam">{{__('Exam') }}</label>
            <select name="exam" id="exam" class="form-control">
                <option value="" selected disabled>Select exam</option>
                @foreach ($examList as $exam)
                    <option value="{{$exam->id}}">{{$exam->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
        <button class="btn btn-primary search" type="button" id="search">
           <i class="fa fa-search"></i>   Search
        </button>
     </form>

    <div class="row">
    </div>
        <div class="col-12" >
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <h4 class="header-title">Note List</h4>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns" id="transprint">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead class=" bg-info text-white">
                                <tr>
                                    <th>Evaluation</th>
                                    <th data-priority="1">Classe</th>
                                    <th data-priority="3">Course</th>
                                    <th data-priority="3">Teacher Name</th>
                                    <th data-priority="1">Student Name</th>
                                    <th data-priority="3">Note</th>
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
{{-- <button type="button" class="btn btn-outline-primary btn-sm btn-block" id="print" onclick="printDiv('transPrint')"> Print</button> --}}
@endsection

@section('js')
<!-- Required datatable js -->
<script src="{{asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

<script type="text/javascript">

        //  function printDiv(divName) {
        //     var printContents = document.getElementById(divName).innerHTML;
        //     var originalContents = document.body.innerHTML;
        //     //myPrintWindow.document.getElementById('hidden_div').style.display='block'
        //     document.body.innerHTML = printContents;
        //     window.print();
        //     document.body.innerHTML = originalContents;
        //  }

    $(document).ready(function(){

        $("#search").on('click',function(event) {

        event.preventDefault();
        var classe = $("#classe").val();
        var exam = $("#exam").val();

        $.ajax({
            type: "POST",
            url: "/evaluation/SearchNoteByClasse",
            data: {
                classe: classe,
                exam:exam
            },
            dataType:'json',
            headers:{'X-CSRF-Token':csrfToken},
            success: function(results) {
            var resultTable = $("#tabcontent");
            resultTable.empty();
            // console.log(results);
               results.datas.forEach(data =>{
               content = `<tr>
                <td>${data.evaluation}</td>
                <td>${data.classe}</td>
                <td>${data.course}</td>
                <td>${data.teacher}</td>
                <td>${data.student}</td>
                <td>${data.note}</td>

                </tr>`;
                resultTable.append(content);
                  });
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




         });
</script>

@endsection


