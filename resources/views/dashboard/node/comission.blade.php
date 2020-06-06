@extends('layouts.dashboard')

@section('title', 'Comission Detail report')

@section('pagecontent')

    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-responsive table-responsive-data2">
                <table id="nodetable" width="100%" class="table table-striped table-bordered">
                    <thead>
                    <tr>

                        <th>Sno</th>
                        <th>Name</th>
                        <th>Distributor Code</th>
                        <th>Coupon</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>P-Date</th>
                        <th>Status</th>


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

            var id="{{$id}}";

            var url="{{ URL::to('/dashboard/comissionserverside') }}";
            var full_url=url+"/"+id;
            var row=0;
            var table = $('#nodetable').DataTable({

                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                ajax: full_url,
                columns: [



                    {
                        targets: 1,
                        data: id,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }


                    },
                    { data: 'name' },
                    { data: 'distributor_id' },
                    { data: 'coupon' },
                    { data: 'cdate' },
                    { data: 'amount' },
                    { data: 'payout_date' },
                    { data: 'status' },



                ]
            });



            $('#nodetable tbody').on( 'click', '#btncomission', function () {
                var data = table.row($(this).parents('tr')).data();
                if(data.status=='active'|| data.status=='inactive')
alert(data.id);
                   // Edit(data.id);
            } );

            $('#nodetable tbody').on( 'click', '#btntree', function () {
                var data = table.row($(this).parents('tr')).data();
                if(data.status=='active'|| data.status=='inactive')
                {
                    var url="{{ URL::to('dashboard/tree/tree/')}}";
                        var full_url=url+"/" +data.id;
                       // alert(full_url);
                    window.location.href=full_url;
                }


            } );

            $('#nodetable tbody').on( 'click', '#btn-status', function () {
                var data = table.row($(this).parents('tr')).data();
                //alert(data.coupon_code);
                if(data.status=='active')
                {

                    var con = confirm('Do you want to deactivate this Person : ' + data.name + " ?");

                    if(con == true){

                        $.ajax({
                            url: "{{ URL::to('dashboard/node/deactivate') }}",
                            type: "POST",
                            data: {id: data.id, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                window.location.reload();
                            }
                        });
                    }

                }

                if(data.status=='inactive')
                {
                    var con = confirm('Do you want to Activate this person : ' + data.name + " ?");
                    if(con == true){

                        $.ajax({
                            url: "{{ URL::to('dashboard/node/activate') }}",
                            type: "POST",
                            data: {id: data.id, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                window.location.reload();
                            }
                        });
                    }


                }


            } );

            $('#nodetable tbody').on( 'click', '#btndelete', function () {
                var data = table.row($(this).parents('tr')).data();
                if(data.status=='active'|| data.status=='inactive')
                {
                    //Delete(data.id,data.name,data.coupon_code);
                }

            } );
        });

        function Edit(id) {

            $.ajax({
                url: "{{ URL::to('/dashboard/node/nodeedit') }}",
                type: "POST",
                data: {id: id, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    var json = $.parseJSON(data);

                    console.log(json);

                    $('#id').val(json.id);
                    $('#name').val(json.name);
                    $('#fname').val(json.f_name);
                    $('#dob').val(json.dob);
                    //$('#gender').val(json.sex);
                    $("input[name=gender"+"][value="+json.sex+"]").prop('checked', true);
                    $('#mobile').val(json.mobile);
                    $('#email').val(json.email);
                    $('#aadhar').val(json.aadhar);
                    $('#pan').val(json.pan);
                    $('#address').val(json.address);
                    $('#accno').val(json.account_no);
                    $('#ifsccode').val(json.ifsc_code);
                    $('#bankname').val(json.bank_name);
                    $('#branchname').val(json.branch_name);
                    $('#nominee').val(json.nominee_name);
                    $('#relationship').val(json.nominee_relationship);
                    $('#myModal').modal('show');
                }
            });
        }

        function Delete(id,name,coupon) {
            var con = confirm('Are you want Delete ' + name +"'s coupon "+coupon+" ?");
            if(con == true){
                $.ajax({
                    url: "{{ URL::to('dashboard/coupon/delete') }}",
                    type: "POST",
                    data: {id: id, "_token": "{{ csrf_token() }}"},
                    success: function (data) {
                        window.location.reload();
                    }
                });
            }
        }
    </script>
@endsection


