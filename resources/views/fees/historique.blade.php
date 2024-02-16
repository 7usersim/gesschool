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
    <div class="row">
    </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <h4 class="header-title">Payment History</h4>
                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="tech-companies-1" class="table table-striped">
                                <thead class=" bg-info text-white">
                                <tr>
                                    <th data-priority="1">Name</th>
                                    <th data-priority="1">Amount</th>
                                    <th data-priority="3">Paid</th>
                                    <th data-priority="1">Left to pay</th>
                                    <th data-priority="3">Payment Date</th>
                                    <th data-priority="3">Method</th>
                                    <th data-priority="6">Reference</th>
                                    <th data-priority="6">Status</th>
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

let getnamestudent = (id) => {
    // var name = '';
    let name = $.ajax({
        type: "POST",
        url: "/student/getStudentName",
        dataType:'json',
        data:{'id':id},
        headers:{'X-CSRF-Token':csrfToken},
        success: function(results) {
            // console.log(results.name[0].first + ' ' + results.name[0].last);
            // console.log(name);
            name =  results.name[0].first + ' ' + results.name[0].last;
            // console.log(name);
            return name;
        }
    });
    // console.log(name);
    return name;
}

$(document).ready(function(){
    $.ajax({
            type: "POST",
            url: "/student/historiqueFees",
            dataType:'json',
            headers:{'X-CSRF-Token':csrfToken},
            success: function(results) {
            var resultTable = $("#tabcontent");
            resultTable.empty();
            // console.log(typeof results.ListHistorique[0].historique);
            results.ListHistorique.forEach(data =>{
                data.historique = JSON.parse(data.historique);
                // $.each(data.historique, (cle, valeur)=>{
                //     let res = `${cle} - - ${valeur}`
                //     console.log(res);
                // })
                // console.log(data.historique[0].student_id);
                let name = getnamestudent(data.historique[0].student_id);
                console.log(name.responseJSON);
                content = `<tr>
                <td>${getnamestudent(data.historique[0].student_id)}</td>
                <td>${data.historique[0].amount}</td>
                <td>${data.historique[0].paid}</td>
                <td>${data.historique[0].left_to_pay}</td>
                <td>${data.historique[0].payment_date}</td>
                <td>${data.historique[0].payment_method}</td>
                <td>${data.historique[0].payment_reference}</td>
                <td>${data.historique[0].payment_status}</td>
                </tr>`;
                resultTable.append(content);
                  });
               }
          });
    })


</script>

@endsection



