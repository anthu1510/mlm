@extends('layouts.dashboard')

@section('title', 'Coupon')

@section('pagecontent')

    {{--<style>
        /* Tabs*/
        section {
            padding: 60px 0;
        }

        section .section-title {
            text-align: center;
            color: #007b5e;
            margin-bottom: 50px;
            text-transform: uppercase;
        }

        #tabs {
            background: #007b5e;
            color: #eee;
        }

        #tabs h6.section-title {
            color: #eee;
        }

        #tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            color: #f3f3f3;
            background-color: transparent;
            border-color: transparent transparent #f3f3f3;
            border-bottom: 4px solid !important;
            font-size: 20px;
            font-weight: bold;
        }

        #tabs .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            color: #eee;
            font-size: 20px;
        }
    </style>--}}

    <form action="{{ URL::to('dashboard/node/save') }}" method="post" id="newnode">
        @csrf
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="u_name"> Name </label>
                    {{--<input type="text" name="u_id" id="u_id">--}}

                    <input type="text" name="name" id="name"
                           class=" form-control validate[required]"
                           value="" placeholder="Enter Name"
                           data-errormessage-value-missing="Name is required!">
                </div>
                <div class="form-group">
                    <label for="u_name">Father/Spouse</label>

                    <input type="text" name="fname" id="fname" class="form-control validate[required]"
                           value="" placeholder="Enter Father/Spouse Name"
                           data-errormessage-value-missing="Father / Spouse  Name is required!">
                </div>
                <div class="form-group">
                    <label for="u_name">D.O.B</label>

                    <input type="date" name="dob" id="dob" class="form-control"
                           value="" placeholder="Select D.O.B">
                </div>
                <div class="form-group">
                    <label for="u_name">Mobile Number</label>

                    <input type="text" name="mobile" id="mobile" class="form-control validate[required]"
                           value="" placeholder="Enter Mobile Number"
                           data-errormessage-value-missing="Mobile Numer is required!"/>
                </div>
                <div class="form-group">
                    <label for="u_name">Aadhar Number</label>
                    <input type="hidden" id="checkaadhar" value="{{ URL::to('dashboard/node/aadharcheck') }}">
                    <input type="text" name="aadhar" id="aadhar"
                           class="validate[required,ajax[ajaxAadharCheck]] form-control"
                           value="" placeholder="Enter Aadhar Number"
                           data-errormessage-value-missing="Aadhar Numer is required!">
                </div>
                <div class="form-group">
                    <label for="lpan">Pan Number</label>
                    <input type="hidden" id="checkpan" value="{{ URL::to('dashboard/node/pancheck') }}">

                    <input type="text" name="pan" id="pan" class="form-control validate[required,ajax[ajaxPanCheck]]"
                           value="" placeholder="Enter PAN Number"
                           data-errormessage-value-missing=" PAN Numer is required!">
                </div>
                <div class="form-group">
                    <label for="u_email">Address</label>
                    <textarea name="address" id="address" class="form-control"
                              value="" placeholder="Address"></textarea>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="u_email">Account Number</label>
                    <input type="text" name="accno" id="accno" class="form-control validate[required]"
                           value="" placeholder="Enter Account Number"
                           data-errormessage-value-missing="Account Numer is required!">
                </div>
                <div class="form-group">
                    <label for="u_email">IFSC Code</label>
                    <input type="text" name="ifsccode" id="ifsccode" class="form-control validate[required]"
                           value="" placeholder="Enter IFSC Code"
                           data-errormessage-value-missing="IFSC Code is required!">
                </div>
                <div class="form-group">
                    <label for="u_email">Bank Name</label>
                    <input type="text" name="bankname" id="bankname" class="form-control validate[required]"
                           value="" placeholder="Enter Bank Name"
                           data-errormessage-value-missing="Bank Name is required!">
                </div>
                <div class="form-group">
                    <label for="u_email">Branch Name</label>
                    <input type="text" name="branchname" id="branchname" class="form-control "
                           value="" placeholder="Enter Branch Name"
                           data-errormessage-value-missing="Branch Name is required!">
                </div>
                <div class="form-group">
                    <label for="u_email">Nominee Name</label>
                    <input type="text" name="nominee" id="nominee" class="form-control validate[required]"
                           value="" placeholder="Enter Nominee Name"
                           data-errormessage-value-missing="Nominee Name is required!">
                </div>
                <div class="form-group">
                    <label for="u_email">Relationship with Nominee Name</label>
                    <input type="text" name="relationship" id="relationship" class="form-control"
                           value="" placeholder="Enter Nominee Name">
                </div>
            </div>
            <div class="col-lg-4">

                <div class="form-group">
                    <label for="u_email">Coupone Code</label>
                    <input type="hidden" id="checkcoupon" value="{{ URL::to('dashboard/node/couponcheck') }}">
                    <input type="text" name="couponcode" id="couponcode"
                           class="validate[required,ajax[ajaxCouponCheck]] form-control"
                           value="" placeholder="Enter Coupon Code"
                           data-errormessage-value-missing="Coupon Code is required!"
                           data-errormessage-custom-error="Let me give you a hint: FG-100-001"
                           data-errormessage="This is the fall-back error message.">
                </div>
                <div class="form-group">
                    <label for="u_email">Sponser Id</label>
                    <input type="hidden" id="checksponser" value="{{ URL::to('dashboard/node/sponsercheck') }}">
                    <input type="text" name="sponserid" id="sponserid"
                           class="validate[required,ajax[ajaxSponserCheck]] form-control"
                           value="" placeholder="Enter Sponser Id"
                           data-errormessage-value-missing="Sponser Id is required!"
                           data-errormessage-custom-error="Let me give you a hint: FG20200305000001"
                           data-errormessage="This is the fall-back error message.">
                </div>
                <div class="form-group">
                    <label for="u_email">Sponser Name</label>
                    <input type="text" name="sponsername" id="sponsername" class="form-control"
                           value="" readonly>
                </div>
                <div class="form-group">
                    <label for="u_email">Sponser Mobile</label>
                    <input type="text" name="sponsermobile" id="sponsermobile" class="form-control"
                           value="" readonly>
                </div>
           <div class="form-group">
               <div class="card">
                   <div class=card-header">Tree Positions</div>
                   <div class="card-body">
                       <div class="custom-control custom-radio custom-control-inline col-lg-3" id="left-lab">
                           <input type="radio" value="left" class="validate[required] custom-control-input" id="tree_left" name="tree_position">
                           <label class="custom-control-label" for="tree_left">Left</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline col-lg-3" id="middle-lab">
                           <input type="radio" value="middle" class="validate[required] custom-control-input" id="tree_middle" name="tree_position">
                           <label class="custom-control-label" for="tree_middle">Middle</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline col-lg-3" id="right-lab">
                           <input type="radio" value="right" class="validate[required] custom-control-input" id="tree_right" name="tree_position">
                           <label class="custom-control-label" for="tree_right">Right</label>
                       </div>
                   </div>
               </div>

           </div>

            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary pull-right">Save</button>
        </div>
    </form>



@endsection

@section('datatables-scripts')
    <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">

    <script
        src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
    <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#newnode").validationEngine({promptPosition: "topLeft:0"});


        });


        $('#sponserid').keyup(function () {
            var val = $(this).val();

            $('input[type="radio"]').prop('checked', false);
            var url = "{{ URL::to('dashboard/node/getsponser') }}";
            $.get(url, {value: val, "_token": "{{ csrf_token() }}"}, function (data) {
               // $('input[type="radio"]').prop('checked', false);
                //console.log(data);
                    if (data === 'error') {
                        $('#sponsername').val('');
                        $('#sponsermobile').val('');
                        $('#sponseraddress').val('');
                       $('#left-lab, #middle-lab, #right-lab').css('display', 'none');

                    } else {
                        var json = JSON.parse(data);
                        $('#sponsername').val(json.name);
                        $('#sponsermobile').val(json.mobile);
                        $('#sponseraddress').val(json.address);
                        setInterval(function () {
                            $('#sponsername').css('color', 'transparent');
                            setTimeout(function () {
                                $('#sponsername').css('color', 'red');
                            }, 500);
                        }, 1000);


                        if (json.l == '') {
                            $('#left-lab').css('display', 'inline');
                            //$('#left-lab').enabled();
                        }
                        if (json.r == '') {
                            $('#middle-lab').css('display', 'inline');

                        }
                        if (json.m == '') {
                            $('#right-lab').css('display', 'inline');

                        }
                    }
                }
            );

        });
    </script>
@endsection


