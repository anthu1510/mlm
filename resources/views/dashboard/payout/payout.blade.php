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
                        <th>Coupon</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>P.Date</th>
                        <th width="2%">Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>
@endsection

@section('datatables-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">
    @include('dashboard.users.new_users')
    <script src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
    <script>
        $(document).ready(function() {

            $("#pass-update").validationEngine();
            var table = $('#nodetable').DataTable({
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                ajax: '{{ URL::to('/dashboard/payoutserverside') }}',
                columns: [
                    {
                        data: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'node_id' },
                    { data: 'node_code' },
                    { data: 'node_name' },
                    { data: 'coupon' },
                    { data: 'cdate' },
                    { data: 'amount' },
                    { data: 'status' },
                    { data: 'payout_date' },
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






        });




    </script>
@endsection


