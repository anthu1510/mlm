@extends('layouts.dashboard')

@section('title', 'Payout')

@section('pagecontent')

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
                        <th>F Name</th>
                        <th>Mobile</th>
                        <th>Aadhar</th>
                        <th>Amount</th>
                        <th>PDate</th>
                        <th width="2%">Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>


@endsection

@section('model-content')
    <!-- The Modal -->
    <div class="modal" id="modalPay">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Payout Edit</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">


                    <form action="{{ URL::to('dashboard/payout/updatesave') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="chequeno">Cheque No</label>
                            {{--<input type="text" name="u_id" id="u_id">--}}
                            <input type="hidden" id="id" name="id">
                            <input type="text" name="chequeno" id="chequeno" class="form-control"
                                   value="" placeholder="Enter Cheque No">
                        </div>
                        <div class="form-group">
                            <label for="trackingno">Tracking  Number</label>

                            <input type="text" name="trackingno" id="trackingno" class="form-control"
                                   value="" placeholder="Enter Tracking Number">
                        </div>
                        <div class="form-group">
                            <label for="collectedon">Collected on</label>
                            <input type="text" name="collectedon" id="collectedon" class="form-control datetimepicker"
                                   value="" placeholder="Collected On ">
                        </div>

                        <div class="form-group">
                            <label for="note">Comments</label>
                            <textarea name="note" id="note" class="form-control"
                                      value="" placeholder="Notes"></textarea>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary pull-right">Update</button>
                        </div>
                    </form>


                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@endsection






@section('datatables-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/datetimepicker/build/jquery.datetimepicker.min.css') }}">

    <script src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
    <script src="{{ asset('assets/vendor/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $("#pass-update").validationEngine();
            $('.datetimepicker').datetimepicker({
                timepicker:false,
                format:'Y-m-d'
            });
            var table = $('#nodetable').DataTable({
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                ajax: '{{ URL::to('/dashboard/payoutlistserverside') }}',
                columns: [
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'id' },
                    { data: 'distributor_id' },
                    { data: 'name' },
                    { data: 'f_name' },
                    { data: 'mobile' },
                    { data: 'aadhar' },
                    { data: 'total' },
                    { data: 'pdate' },
                    {
                        targets: 9,
                        data: null,
                        defaultContent: '<button id="btncomission" title="View Comission" type="button" class="btn btn-primary btn-sm">' +
                            '<i class="fa fa-sticky-note" aria-hidden="true"></i></button>'


                    }

                ],"columnDefs": [
                    { "visible": false, "targets": 1 },

                ]

            });


            $('#nodetable tbody').on('click', '#btncomission', function () {
                var data = table.row($(this).parents('tr')).data();
                var id = data.id;

                Edit(id);
                /*var url = "{{ URL::to('dashboard/comission/comission/')}}";
                var full_url = url + "/" + id;
                window.location.href = full_url;*/
            });



            function Edit(id) {

                $.ajax({
                    url: "{{ URL::to('/dashboard/payout/payoutedit') }}",
                    type: "POST",
                    data: {id: id, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        var json = $.parseJSON(data);
                        $('#id').val(json.id);
                        $('#chequeno').val(json.cheque_no);
                        $('#trackingno').val(json.tracking_no);
                        $('#collectedon').val(json.collected_on);
                        $('#note').val(json.note);
                        $('#modalPay').modal('show');
                    }
                });
            }



        });




    </script>
@endsection


