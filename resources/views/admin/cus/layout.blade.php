<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <!-- <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png"> -->
    <title>@yield('title')</title>

    {{-- <link href="{{ asset('css/lib/chartist/chartist.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/lib/owl.carousel.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/lib/owl.theme.default.min.css') }}" rel="stylesheet" /> --}}

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/lib/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style type="text/css">
    .modal-confirm {        
        color: #636363;
        width: 400px;
    }
    .modal-confirm .modal-content {
        padding: 20px;
        border-radius: 5px;
        border: none;
        text-align: center;
        font-size: 14px;
    }
    .modal-confirm .modal-header {
        border-bottom: none;   
        position: relative;
    }
    .modal-confirm h4 {
        text-align: center;
        font-size: 26px;
        margin: 30px 0 -10px;
    }
    .modal-confirm .close {
        position: absolute;
        top: -5px;
        right: -2px;
    }
    .modal-confirm .modal-body {
        color: #999;
    }
    .modal-confirm .modal-footer {
        border: none;
        text-align: center;     
        border-radius: 5px;
        font-size: 13px;
        padding: 10px 15px 25px;
    }
    .modal-confirm .modal-footer a {
        color: #999;
    }       
    .modal-confirm .icon-box {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        border-radius: 50%;
        z-index: 9;
        text-align: center;
        border: 3px solid #f15e5e;
    }
    .modal-confirm .icon-box i {
        color: #f15e5e;
        font-size: 46px;
        display: inline-block;
        margin-top: 13px;
    }
    .modal-confirm .btn {
        color: #fff;
        border-radius: 4px;
        background: #60c7c1;
        text-decoration: none;
        transition: all 0.4s;
        line-height: normal;
        min-width: 120px;
        border: none;
        min-height: 40px;
        border-radius: 3px;
        margin: 0 5px;
        outline: none !important;
    }
    .modal-confirm .btn-info {
        background: #c1c1c1;
    }
    .modal-confirm .btn-info:hover, .modal-confirm .btn-info:focus {
        background: #a8a8a8;
    }
    .modal-confirm .btn-danger {
        background: #f15e5e;
    }
    .modal-confirm .btn-danger:hover, .modal-confirm .btn-danger:focus {
        background: #ee3535;
    }
</style>
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                        <!-- Logo icon -->
                        <b><img src="/images/logo.jpg" alt="homepage" class="dark-logo" /></b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        {{-- <span><strong class="dark-logo"> Honeypays </strong></span> --}}
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <!-- Messages -->
                    
                        <!-- End Messages -->
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset($user->identity)}}" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="/admin/cus/edit"><i class="ti-user"></i> Profile</a></li>
                                    <li><a href="/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li> <a href="/admin/cus/{{$user->id}}" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a>
                        </li>
                        <li> <a href="/admin" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Admin Dashboard</span></a>
                        </li>
                        <li class="nav-label">Options</li>
                        <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="ti-user"></i><span class="hide-menu">Profile</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="/admin/cus/edit/{{$user->id}}">Edit Profile</a></li>
                                
                            </ul>
                        </li>
                        @if(!empty($user->mentor))
                        <li> <a href="/admin/cus/referals/{{$user->id}}" aria-expanded="false"><i class="fa fa-eye"></i><span class="hide-menu">Referals</span></a>
                        </li>
                        @endif
                        <li class="nav-label">Search</li>
                        <li> <a href="/admin/cus/history/{{$user->id}}" aria-expanded="false"><i class="fa fa-search"></i><span class="hide-menu">All History</span></a></li>
                        <li class="nav-label">Moderate</li>
                        @if($user->active)
                        <li> <a href="#suspend" aria-expanded="false" data-toggle="modal"><i class="fa fa-ban"></i><span class="hide-menu">Suspend</span></a></li>
                        @else
                        <li> <a href="/admin/unsuspend/{{$user->id}}" aria-expanded="false"><i class="fa fa-unlock-alt"></i><span class="hide-menu">Unsuspend</span></a></li>
                        @endif

                        @if(empty($user->mentor))
                        <li> <a href="/admin/makementor/{{$user->id}}" aria-expanded="false"><i class="fa fa-cog"></i><span class="hide-menu">Make Mentor</span></a></li>
                        @endif

                        <li> <a href="#delete" aria-expanded="false"  data-toggle="modal"><i class="fa fa-trash"></i><span class="hide-menu">Delete</span></a></li>

                        </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">@yield('bread')</h3> </div>
                {{-- <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div> --}}
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
               
               @yield('content')

                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
            <!-- footer -->
            <!-- Modal HTML -->
            
            <footer class="footer"> © {{date('Y')}} All rights reserved. <a href="https://honeypays.com.ng">Honeypays Micro Credit Investment Ltd</a></footer>
            <!-- End footer -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->

    <!-- The Modal Delete -->
<div class="modal" id="delete">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
            <div class="icon-box">
                    <i class="material-icons fa fa-trash"></i>
                </div>
        <h4 class="modal-title">Are you sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            <p>Do you really want to delete these customer? This process cannot be undone.</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
        <a href="/admin/delete/{{$user->id}}"><button type="button" class="btn btn-danger">Delete</button></a>
      </div>

    </div>
  </div>
</div>
         <!-- The Modal Suspend -->
<div class="modal" id="suspend">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
            <div class="icon-box">
                    <i class="material-icons fa fa-ban"></i>
                </div>
        <h4 class="modal-title">Are you sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
      <form method="GET" action="/admin/suspend/{{$user->id}}">
      @csrf
            <p>Do you really want to Suspend these customer?</p>

            <div class="form-group {{$errors->has('reason') ? 'invalid-feedback' : ''}}">
            <input type="text" name="reason" class="form-control" placeholder ="Reason" required>
            @if ($errors->has('reason'))
        <span class="invalid-feedback">
            <strong>{{ $errors->first('reason') }}</strong>
        </span>
    @endif
        </div>

        <button type="submit" class="btn btn-danger">Suspend</button>

      </form>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
    <!-- All Jquery -->
    <script src="{{ asset('js/lib/jquery/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('js/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <!--stickey kit -->
    <script src="{{ asset('js/lib/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>


   {{--  <script src="{{ asset('js/lib/datamap/d3.min.js') }}"></script>
    <script src="{{ asset('js/lib/datamap/topojson.js') }}"></script>
    <script src="{{ asset('js/lib/datamap/datamaps.world.min.js') }}"></script>
    <script src="{{ asset('js/lib/datamap/datamap-init.js') }}"></script>

    <script src="{{ asset('js/lib/weather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('js/lib/weather/weather-init.js') }}"></script>
    <script src="{{ asset('js/lib/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/lib/owl-carousel/owl.carousel-init.js') }}"></script> 


    <script src="{{ asset('js/lib/chartist/chartist.min.js') }}"></script>
    <script src="{{ asset('js/lib/chartist/chartist-plugin-tooltip.min.js') }}"></script>
    <script src="{{ asset('js/lib/chartist/chartist-init.js') }}"></script>--}}
    <!--Custom JavaScript -->
    <script src="{{ asset('js/custom.min.js') }}"></script>

    <script src="{{ asset('js/lib/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/lib/datatables/datatables-init.js') }}"></script>



</body>

</html>