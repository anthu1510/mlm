@extends('layouts.dashboard')

@section('title', 'Payout')

@section('pagecontent')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">Generate Payout</div>
                <div class="card-body">
                    <form id="validateForm" method="post" action="{{ URL::to('dashboard/payout/payoutgenerateview') }}">
                        @csrf
                        <div class="row">

                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="title">To Date</label>
                                    <input type="text" autocomplete="off" class="form-control validate[required] datetimepicker" name="to_date" id="to_date" placeholder="Select the to Date">
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <label for="amount">Minimum Payout Amount</label>
                                    <input type="text" autocomplete="off" class="form-control validate[required] " name="amount" id="amount" placeholder="Please enter Amount " value="500">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label for="comments">Comments</label>
                                    <input type="text" autocomplete="off" id="comments" class="form-control  " name="comments"  placeholder="Comments" >
                                </div>
                            </div>



                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label class="mt-lg-3 mt-md-3" for=""></label>
                                    <button class="btn btn-primary form-control">Search</button>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <label class="mt-lg-3 mt-md-3" for=""></label>
                                    <button type="button" id="btn-generate" class="btn btn-primary form-control">Generate</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if(isset($generate))
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <div class="table-responsive table-responsive-data2">
                    <table id="nodetable" width="100%" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Sno</th>
                            <th>Id</th>
                            <th>Distributor Code</th>
                            <th>Name</th>
                            <th>F-Name</th>
                            <th>Mobile</th>
                            <th>Aadhar</th>
                            <th>Total</th>

                            <th width="2%">Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- END DATA TABLE -->
            </div>
        </div>
    @endif
@endsection

@section('datatables-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datetimepicker/build/jquery.datetimepicker.min.css') }}">

    <script src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datetimepicker').datetimepicker({
                timepicker:false,
                format:'Y-m-d'
            });
            @if(isset($generate))
            $("#amount").val("{{$generate['amount']}}");
            $("#to_date").val("{{$generate['to_date']}}");
            $("#comments").val("{{$generate['comments']}}");
            @endif

            $("#validateForm").validationEngine();
                @if(isset($generate))
            var table = $('#nodetable').DataTable({
                    "order": [[ 0, "desc" ]],
                    "processing": true,
                    "serverSide": true,
                    ajax: '{{ URL::to('/dashboard/payoutgenerateserverside') }}',
                    columns: [
                        {
                            data: 'node_id',
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { data: 'node_id' },
                        { data: 'distributor_id' },
                        { data: 'name' },
                        { data: 'f_name' },
                        { data: 'mobile' },
                        { data: 'aadhar' },
                        { data: 'total' },
                        {
                            targets: 9,
                            data: null,
                            defaultContent: '<button id="btncomission" title="View Comission" type="button" class="btn btn-primary btn-sm">' +
                                '<i class="fa fa-eye" aria-hidden="true"></i></button>'


                        }

                    ],"columnDefs": [
                        { "visible": false, "targets": 1 },

                    ]

                });


            $('#nodetable tbody').on('click', '#btncomission', function () {
                var data = table.row($(this).parents('tr')).data();
                var id = data.node_id;
                var url = "{{ URL::to('dashboard/comission/comission/')}}";
                var full_url = url + "/" + id;
                window.location.href = full_url;
            });
            @endif

        });

$("#btn-generate").click(function () {
var to_date=$("#to_date").val();
var amount=$("#amount").val();
var comments=$("#comments").val();
$.post("{{ URL::to('dashboard/payout/makepayout/')}}", {amount:amount,todate:to_date,comments:comments,"_token": "{{ csrf_token() }}"},function (data) {
console.log(data);
})
})


    </script>
@endsection


