@extends('layouts.dashboard')

@section('title', 'Coupon')

@section('pagecontent')
    <div class="row">
        <div class="col-lg-4 offset-lg-4 col-md-4 offset-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    Coupon Generation
                </div>
                <div class="card-body">
                    <form action="{{ URL::to('dashboard/coupon/save') }}" method="post" id="newnode">
                        @csrf
                        <div class="form-group">
                            <label for="u_name">No of Coupons</label>
                            {{--<input type="text" name="u_id" id="u_id">--}}
                            <input type="hidden" id="checkaadhar" value="{{ URL::to('dashboard/node/aadharcheck') }}">
                            <input type="text" name="ncoupon" id="ncoupon" class="form-control"
                                   value="" placeholder="Number of Coupons">
                        </div>
                        <div class="form-group">
                            <label for="u_name">Name</label>
                            {{--<input type="text" name="u_id" id="u_id">--}}
                            <input type="text" name="name" id="name"
                                   class="validate[required,ajax[ajaxAadharCheck]] form-control"
                                   value="" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="u_name">Mobile Number</label>

                            <input type="text" name="mobile" id="mobile" class="form-control"
                                   value="" placeholder="Enter Mobile Number">
                        </div>
                        <div class="form-group">
                            <label for="u_email">Email</label>
                            <input type="text" name="email" id="email" class="form-control"
                                   value="" placeholder="Enter Email">
                        </div>

                        <div class="form-group">
                            <label for="u_email">Comments</label>
                            <textarea name="comments" id="comments" class="form-control"
                                      value="" placeholder="Comments"></textarea>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary pull-right">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('datatables-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">

    <script src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#newnode").validationEngine();

        });
    </script>
@endsection


