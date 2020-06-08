@extends('layouts.dashboard')

@section('title', 'Coupon Active List')
@section('additional-css')
    <style>
        tfoot input {
            width: 100%;
            padding: 1px;
            box-sizing: border-box;
        }
    </style>
@endsection
@section('pagecontent')
    <div class="row mb-2">
        <div class="col-lg-1">
            <a href="{{ URL::to('/dashboard/coupon') }}"  class="btn btn-outline-primary"

            >Create Coupon
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <table id="coupontable"   class="table  table-striped table-bordered responsive nowrap">
                <thead>
                <tr>
                    <th width="5%">Id</th>
                    <th width="5%">Ord.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Coupon Code</th>
                    <th>Date</th>
                    <th width="2%">Status</th>
                    <th width="8%">Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th width="5%">Id</th>
                    <th width="5%">Ord.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Coupon Code</th>
                    <th>Date</th>
                    <th width="2%">Status</th>
                    <th width="8%"></th>
                </tr>
                </tfoot>
            </table>
            <div class="table-responsive table-responsive-data2">

            </div>
            <!-- END DATA TABLE -->
        </div>
    </div>
@endsection

@section('model-content')
    @include('dashboard.coupon.modal')
@endsection

@section('datatables-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">
    @include('dashboard.users.new_users')
    <script src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Setup - add a text input to each footer cell
            $('#coupontable tfoot th').each( function () {
                var title = $(this).text();
                    $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

            } );



            $("#pass-update").validationEngine();
            var table = $('#coupontable').DataTable({
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                ajax: '{{ URL::to('/dashboard/couponactiveserverside') }}',
                columns: [
                    { data: 'id'},
                    { data: 'gen_id' },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'mobile' },
                    { data: 'coupon_code' },

                    {
                        data: 'c_date'},
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
                            '<i class="fa fa-edit" aria-hidden="true"></i></button>' +
                            '<button id="btndelete" type="button" title="Delete" class="btn btn-danger btn-sm">' +
                            '<i class="fa fa-trash" aria-hidden="true"></i>' +
                            '</button>'
                    }
                ],initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;

                        $( 'input', this.footer() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );
                }

            });



            $('#coupontable tbody').on( 'click', '#btnedit', function () {
                //var data = table.row($(this).parents('tr')).data(); // non responsive method

                var current_row = $(this).parents('tr');//Get the current row
                if (current_row.hasClass('child')) {//Check if the current row is a child row
                    current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
                }
                var data = table.row(current_row).data();//At this point, current_row refers to a valid row in the table, whether is a child row (collapsed by the DataTable's responsiveness) or a 'normal' row

                if(data.status=='active'|| data.status=='inactive')

                    Edit(data.id);
            } );

            $('#coupontable tbody').on( 'click', '#btn-status', function () {
             //   var data = table.row($(this).parents('tr')).data();   // non responsive method

                var current_row = $(this).parents('tr');//Get the current row
                if (current_row.hasClass('child')) {//Check if the current row is a child row
                    current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
                }
                var data = table.row(current_row).data();//At this point, current_row refers to a valid row in the table, whether is a child row (collapsed by the DataTable's responsiveness) or a 'normal' row


                //alert(data.coupon_code);
                if(data.status=='active')
                {

                    var con = confirm('Do you want to deactivate this coupon : ' + data.coupon_code + " ?");

                    if(con == true){

                        $.ajax({
                            url: "{{ URL::to('dashboard/coupon/deactivate') }}",
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
                    var con = confirm('Do you want to Activate this coupon : ' + data.coupon_code + " ?");
                    if(con == true){

                        $.ajax({
                            url: "{{ URL::to('dashboard/coupon/activate') }}",
                            type: "POST",
                            data: {id: data.id, "_token": "{{ csrf_token() }}"},
                            success: function (data) {
                                window.location.reload();
                            }
                        });
                    }


                }


            } );

            $('#coupontable tbody').on( 'click', '#btndelete', function () {
                //var data = table.row($(this).parents('tr')).data(); // non responsive method


                var current_row = $(this).parents('tr');//Get the current row
                if (current_row.hasClass('child')) {//Check if the current row is a child row
                    current_row = current_row.prev();//If it is, then point to the row before it (its 'parent')
                }
                var data = table.row(current_row).data();//At this point, current_row refers to a valid row in the table, whether is a child row (collapsed by the DataTable's responsiveness) or a 'normal' row


                if(data.status=='active'|| data.status=='inactive')
                    Delete(data.id,data.name,data.coupon_code);
            } );
        });

        function Edit(id) {

            $.ajax({
                url: "{{ URL::to('/dashboard/coupon/couponedit') }}",
                type: "POST",
                data: {id: id, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    var json = $.parseJSON(data);

                    $('#id').val(json.id);
                    $('#name').val(json.name);
                    $('#email').val(json.email);
                    $('#mobile').val(json.mobile);
                    $('#comments').val(json.comments);
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


