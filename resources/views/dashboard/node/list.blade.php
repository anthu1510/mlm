@extends('layouts.dashboard')

@section('title', 'Node List')

@section('pagecontent')
    <div class="row mb-2">
        <div class="col-lg-1">
            <a href="{{ URL::to('/dashboard/node/create') }}"  class="btn btn-outline-primary"

            >Create Node
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-responsive table-responsive-data2">
                <table id="nodetable" width="100%" class="table table-striped table-bordered responsive nowrap">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>

                        <th>Distributor Code</th>
                        <th>Sponser Name</th>
                        <th>Sponser Code</th>
                        <th width="5%">Coupon Code</th>
                        <th>Date</th>
                        <th width="5%">Status</th>
                        <th width="2%">Action</th>

                        <th>F Name</th>
                        <th>Aadhar</th>
                        <th>PAN</th>
                        <th>Address</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Acc.No</th>
                        <th>Bank Name</th>
                        <th>Nominee Name</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>

@endsection

@section('model-content')
    @include('dashboard.node.modal')
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
                ajax: '{{ URL::to('/dashboard/nodeserverside') }}',
                columns: [
                    { data: 'id' },
                    { data: 'name',
                    targets:1,
                        render: function ( data, type, row, meta ) {

                                 return data.substring(0, 15);

                    }
                    },
                    { data: 'distributor_id' },
                    { data: 'sponser_name',
                        targets:3,
                        render: function ( data, type, row, meta ) {

                            return data.substring(0, 15);

                        }  },
                    { data: 'sponser_id'},
                    { data: 'coupon_code' },
                    { data: 'cdate' },
                    {
                        data: 'status',
                        targets:7,
                        render: function ( data, type, row, meta ) {
                            /* if(data=='active'){
                                 return '<a href="'+data+'">active</a>';
                             }
                             else
                             {
                                 return '<a href="'+data+'">Inactive</a>';
                             }*/

                            var color = ' badge badge-';
                            if(data == 'active') {
                                color += 'success';
                            }
                            else if(data =='inactive') {
                                color += 'danger';
                            }
                            else  {
                                color += 'warning';
                            }

                            // return '<button class="'+color+'">'+data+'</button>';
                            return '<span style="cursor: pointer" id="btn-status" class="'+color+'">'+data+'</span>';
                        }

                    },
                    {
                        targets: 8,
                        data: null,
                        defaultContent: '<button id="btnedit" title="Edit" type="button" class="btn btn-primary btn-sm">' +
                            '<i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;&nbsp;'


                    },
                    { data: 'f_name' },
                    { data: 'aadhar' },
                    { data: 'pan' },
                    { data: 'address' },
                    { data: 'mobile' },
                    { data: 'email' },
                    { data: 'account_no' },
                    { data: 'bank_name' },
                    { data: 'nominee_name' },

                ],
                "columnDefs": [
                    { "visible": false, "targets": 9 },
                    { "visible": false, "targets": 10 },
                    { "visible": false, "targets": 11 },
                    { "visible": false, "targets": 12},
                    { "visible": false, "targets": 13 },
                    { "visible": false, "targets": 14 },
                    { "visible": false, "targets": 15 },
                    { "visible": false, "targets": 16 },
                    { "visible": false, "targets": 17 }
                ]
            });



            $('#nodetable tbody').on( 'click', '#btnedit', function () {
               // var data = table.row($(this).parents('tr')).data();  // non responsive method
                var current_row = $(this).parents('tr');//Get the current row
                if (current_row.hasClass('child')) {//Check if the current row is a child row
                    current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
                }
                var data = table.row(current_row).data();//At this point, current_row refers to a valid row in the table, whether is a child row (collapsed by the DataTable's responsiveness) or a 'normal' row

                if(data.status=='active'|| data.status=='inactive')

                    Edit(data.id);
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
                //var data = table.row($(this).parents('tr')).data();  // non responsive method
                var current_row = $(this).parents('tr');//Get the current row
                if (current_row.hasClass('child')) {//Check if the current row is a child row
                    current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
                }
                var data = table.row(current_row).data();//At this point, current_row refers to a valid row in the table, whether is a child row (collapsed by the DataTable's responsiveness) or a 'normal' row

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


