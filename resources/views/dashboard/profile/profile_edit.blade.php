@extends('layouts.dashboard')

@section('title', 'Profile')

@section('pagecontent')
    <div class="row">
        <div class="col-lg-4 offset-lg-4 col-md-4 offset-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    Profile Edit
                </div>
                <div class="card-body">
                    <form action="{{ URL::to('dashboard/profileupdate') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="u_name">Name</label>
                            <input type="hidden" name="u_id" id="u_id" value="{{ $getuser->user_id }}">
                            <input type="text" name="u_name" id="u_name" class="form-control"
                                   value="{{ $getuser->name }}" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="u_email">Email</label>
                            <input type="text" name="u_email" id="u_email" class="form-control"
                                   value="{{ $getuser->email }}" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary pull-right">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
