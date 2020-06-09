@extends('layouts.dashboard')

@section('title', 'Users')

@section('pagecontent')
    <div class="row mb-2">
        <div class="col-lg-1">
            <button type="button" class="btn btn-outline-primary"
                    data-toggle="modal"
                    data-target="#newusers">New User
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- DATA TABLE -->
            <div class="table-responsive table-responsive-data2">
                <table id="userstbl" width="100%" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
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
    @include('dashboard.users.new_users')
@endsection

@section('datatables-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">

    <script src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#pass-update").validationEngine();
            var table = $('#userstbl').DataTable({
                "order": [[ 0, "desc" ]],
                "processing": true,
                "serverSide": true,
                ajax: '{{ URL::to('/dashboard/userserverside') }}',
                columns: [
                    { data: 'user_id' },
                    { data: 'name' },
                    { data: 'email' },
                    {
                        targets: 3,
                        data: null,
                        defaultContent: '<button id="btnedit" title="Edit" type="button" class="btn btn-primary btn-sm">' +
                            '<i class="fa fa-edit" aria-hidden="true"></i></button>&nbsp;&nbsp;' +
                            '<button id="btndelete" type="button" title="Delete" class="btn btn-danger btn-sm">' +
                            '<i class="fa fa-trash" aria-hidden="true"></i>' +
                            '</button>'
                    }
                ]
            });

            $('#userstbl tbody').on( 'click', '#btnedit', function () {
                var data = table.row($(this).parents('tr')).data();

                Edit(data.user_id);
            } );

            $('#userstbl tbody').on( 'click', '#btndelete', function () {
                var data = table.row($(this).parents('tr')).data();
                Delete(data.id,data.name);
            } );
        });

        function Edit(id) {
            $.ajax({
                url: "{{ URL::to('/dashboard/users/useredit') }}",
                type: "POST",
                data: {id: id, "_token": "{{ csrf_token() }}"},
                success: function (data) {
                    var json = $.parseJSON(data);
console.log(json);
                        $('#uid').val(json.user_id);
                        $('#name_edit').val(json.name);
                        $('#email_edit').val(json.email);

                    $('#editusers').modal('show');
                }
            });
        }

        function Delete(id,name) {
           var con = confirm('Are you want Delete  : ', +name);
            if(con === 'yes'){
                $.ajax({
                    url: "{{ URL::to('/dashboard/users/delete') }}",
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
