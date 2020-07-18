<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.urbanui.com/justdo/template/demo/horizontal-default-light/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 25 Mar 2020 05:43:03 GMT -->

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>FreelancerIT</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('/vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    @yield('head-css')
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('css/horizontal-layout-light/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('images/icon.png')}}">

</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_horizontal-navbar.html -->
        <div class="horizontal-menu">
            <nav class="navbar top-navbar col-lg-12 col-12 p-0">
                <div class="container">
                    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                        <!-- <a class="navbar-brand brand-logo" href="index-2.html"><img src="https://www.urbanui.com/justdo/template/images/logo-white.svg" alt="logo" /></a> -->
                        <a class="navbar-brand brand-logo" href="index-2.html"><img height="50" src="{{asset('images/logo/logo-resize.png')}}" alt="logo" /></a>
                        <a class="navbar-brand brand-logo-mini" href="index-2.html"><img src="{{asset('images/logo/logo-mini.png')}}" alt="logo" /></a>
                    </div>
                    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                        <ul class="navbar-nav navbar-nav-right">
                            @if(!Auth::check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('login')}}">
                                    <i class="ti-user mx-0"></i>
                                    Đăng nhập
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('register')}}">
                                    <i class="ti-pencil mx-0"></i>
                                    Đăng ký
                                </a>
                            </li>
                            @else
                            <li class="nav-item nav-profile dropdown">
                                <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown" style="width: 150px;">
                                    <div class="d-flex w-100">
                                        <img src="{{asset('images/avatar/'.Auth::user()->avatar)}}" alt="profile" class="align-self-center" />
                                        <div class="align-self-center ml-2">
                                            {{Auth::user()->username}}
                                            <p class="m-0">{{floor(Auth::user()->balances)}} VND</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                    <a class="dropdown-item" href="{{route('profile',Auth::user()->id)}}">
                                        <i class="ti-user text-primary"></i>
                                        Trang cá nhân
                                    </a>
                                    <a class="dropdown-item" href="{{route('account',Auth::user()->id)}}">
                                        <i class="ti-settings text-primary"></i>
                                        Quản lý tài khoản
                                    </a>
                                    <a class="dropdown-item" href="{{route('finace')}}">
                                        <i class="ti-money text-primary"></i>
                                        Quản lý tài chính
                                    </a>
                                    <a class="dropdown-item" href="{{route('logout')}}">
                                        <i class="ti-power-off text-primary"></i>
                                        Đăng xuất
                                    </a>
                                </div>
                            </li>
                            @endif
                        </ul>
                        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                            <span class="ti-menu"></span>
                        </button>
                    </div>
                </div>
            </nav>
            <!-- <nav class="bottom-navbar">
        <div class="container">
          <ul class="nav page-navigation">
            <li class="nav-item">
              <a class="nav-link" href="{{route('daskboard')}}">
                <i class="ti-home menu-icon"></i>
                <span class="menu-title">Tổng quan</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{route('myproject')}}">
                <i class="ti-briefcase menu-icon"></i>
                <span class="menu-title">Dự án của tôi</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('postproject')}}" class="nav-link">
                <i class="ti-upload menu-icon"></i>
                <span class="menu-title">Đăng dự án</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="ti-search menu-icon"></i>
                <span class="menu-title">Tìm kiếm</span>
                <i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">
                  <li class="nav-item"><a class="nav-link" href="{{route('searchp')}}">Dự án</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{route('searchu')}}">Người dùng</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </nav> -->
        </div>

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="main-panel">
                <div class="index-wallpaper">
                    <div class="content-wrapper text-center">
                        @yield('content-wrapper')
                        <h3 style="color: white;">Chào mừng đến với Freelancer IT</h3>
                        <h4 style="color: white;">Bạn đang tuyển người để thực hiện một dự án?</h4>
                        <h4 style="color: white;">Bạn là một freelancer đang đang tìm kiếm công việc?</h4>
                        <h4 style="color: white;">Hãy <a href="{{route('login')}}">đăng nhập</a> và sử dụng Freelancer IT ngay</h4>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="w-100 clearfix">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2019 Freelancer IT. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Made by Vo Minh Tri with <i class="ti-heart text-danger ml-1"></i></span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{asset('vendors/chart.js/Chart.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <script src="{{asset('js/settings.js')}}"></script>
    <script src="{{asset('js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="{{asset('js/dashboard.js')}}"></script>
    <script src="{{asset('js/todolist.js')}}"></script>
    <!-- End custom js for this page-->
    @yield('foot-script')
</body>


<!-- Mirrored from www.urbanui.com/justdo/template/demo/horizontal-default-light/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 25 Mar 2020 05:43:26 GMT -->

</html>