@extends('layouts.layout')

@section('additional-css')
    @if(Request::segment(2) != '' && Request::segment(2) != 'profile' && Request::segment(2) != 'passwordchange')
        <link href="{{ asset('assets/vendor/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css') }}" rel="stylesheet" media="all">
      <link href="{{ asset('assets/vendor/DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" media="all">
        {{--<link href="{{ asset('assets/vendor/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.css') }}" rel="stylesheet" media="all">--}}
    @endif

    @if(Request::segment(2) == 'passwordchange' || Request::segment(2) == 'users' || Request::segment(2) == 'coupon' )
        <link rel="stylesheet" href="{{ asset('assets/vendor/validation-engine-master/css/validationEngine.jquery.css') }}">
    @endif
@endsection

@section('content')
    @inject('pro', 'App\Http\Controllers\DashboardController')
    <!-- MENU SIDEBAR-->
    <aside class="menu-sidebar d-none d-lg-block">
        <div class="logo">
            <a href="#">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="MLM"/>
            </a>
        </div>
        <div class="menu-sidebar__content js-scrollbar1">
            <nav class="navbar-sidebar">
                <ul class="list-unstyled navbar__list">
                    <li class="{{ Request::segment(1) == '' ? 'active' : '' }}">
                        <a href="{{ URL::to('/dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <li class="{{ Request::segment(2) == 'quote' ? 'active' : '' }}">
                        <a href="{{ URL::to('/dashboard/quote') }}">
                            <i class="fas fa-chart-bar"></i>Quote View</a>
                    </li>
                    <li class="{{ Request::segment(2) == 'purchaseorder' ? 'active' : '' }}">
                        <a href="{{ URL::to('/dashboard/purchaseorder') }}">
                            <i class="fas fa-calendar-alt"></i>PO View</a>
                    </li>
                    <li class="{{ Request::segment(2) == 'users' ? 'active' : '' }}">
                        <a href="{{ URL::to('/dashboard/users') }}">
                            <i class="fa fa-user-plus"></i>Users</a>
                    </li>
                    <li class="has-sub">
                        <a class="js-arrow open" href="#">
                            <i class="fas fa-copy"></i>Coupon</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list" style="display: {{ Request::segment(2) == 'coupon' ? 'block' : 'none' }};">
                            <li class="{{ (Request::segment(2) == 'coupon' && Request::segment(3)=='') ? 'active' : '' }}">
                                <a href="{{ URL::to('/dashboard/coupon') }}">
                                    <i class="fa fa-plus"></i>New</a>
                            </li>
                            <li class="{{ (Request::segment(2) == 'coupon' && Request::segment(3) == 'list') ? 'active' : '' }}">
                                <a href="{{ URL::to('/dashboard/coupon/list') }}">
                                    <i class="fa fa-list"></i>List</a>
                            </li>



                        </ul>
                    </li>

                    <li class="has-sub">
                        <a class="js-arrow open" href="#">
                            <i class="fas fa-copy"></i>Node</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list" style="display: {{ Request::segment(2) == 'node' ? 'block' : 'none' }};">
                            <li class="{{ (Request::segment(2) == 'node' && Request::segment(3)=='create') ? 'active' : '' }}">
                                <a href="{{ URL::to('/dashboard/node/create') }}">
                                    <i class="fa fa-plus"></i>New</a>
                            </li>
                            <li class="{{ (Request::segment(2) == 'node' && Request::segment(3) == 'list') ? 'active' : '' }}">
                                <a href="{{ URL::to('/dashboard/node/list') }}">
                                    <i class="fa fa-list"></i>List</a>
                            </li>



                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>
    <!-- END MENU SIDEBAR-->

    <!-- PAGE CONTAINER-->
    <div class="page-container">
        <!-- HEADER DESKTOP-->
        <header class="header-desktop">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="header-wrap">
                        <h3 class="title-5 m-b-35">@yield('title')</h3>
                        <div class="header-button">
                            <div class="noti-wrap">
                            </div>
                            <div class="account-wrap">

                                <div class="account-item clearfix js-item-menu">
                                    <div class="image">
                                        <img src="{{asset('assets/images/icon/avatar.png')}}"
                                             alt="{{ $pro->getprofile()->name }}"/>
                                    </div>
                                    <div class="content">
                                        <a class="js-acc-btn" href="#">{{ $pro->getprofile()->name }}</a>
                                    </div>
                                    <div class="account-dropdown js-dropdown">
                                        <div class="info clearfix">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="{{asset('assets/images/icon/avatar.png')}}"
                                                         alt="John Doe"/>
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h5 class="name">
                                                    <a href="#">{{ $pro->getprofile()->name }}</a>
                                                </h5>
                                                <span class="email">{{ $pro->getprofile()->email }}</span>
                                                <p style="font-size: xx-small">Admin</p>
                                            </div>
                                        </div>
                                        <div class="account-dropdown__body">
                                            <div class="account-dropdown__item">
                                                <a href="{{ URL::to('/dashboard/profile') }}">
                                                    <i class="zmdi zmdi-account"></i>Profile</a>
                                            </div>
                                            <div class="account-dropdown__item">
                                                <a href="{{ URL::to('/dashboard/passwordchange') }}">
                                                    <i class="zmdi zmdi-settings"></i>Change Password</a>
                                            </div>
                                        </div>
                                        <div class="account-dropdown__footer">
                                            <a href="{{ URL::to('logout') }}">
                                                <i class="zmdi zmdi-power"></i>Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- HEADER DESKTOP-->

        <!-- MAIN CONTENT-->
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    {{ $pro->displayAlert() }}
                    @yield('pagecontent')
                </div>
            </div>
        </div>
        <!-- END MAIN CONTENT-->
        <!-- END PAGE CONTAINER-->

    </div>
@endsection

@section('additional-js')
    @if(Request::segment(2) != '' && Request::segment(2) != 'profile' && Request::segment(2) != 'passwordchange')
        <script src="{{ asset('assets/vendor/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendor/DataTables/DataTables-1.10.18/js/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
        @yield('datatables-scripts')
    @endif

    @if(Request::segment(2) == 'passwordchange')
        <script src="{{ asset('assets/vendor/validation-engine-master/js/languages/jquery.validationEngine-en.js') }}"></script>
        <script src="{{ asset('assets/vendor/validation-engine-master/js/jquery.validationEngine.js') }}"></script>
        <script>
            $(document).ready(function () {
                $("#pass-update").validationEngine();
            });
        </script>
    @endif
@endsection

