<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.urbanui.com/justdo/template/demo/horizontal-default-light/pages/samples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 26 Mar 2020 01:31:01 GMT -->

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đổi mật khẩu Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('css/horizontal-layout-light/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('images/icon.png')}}">
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="main-panel">
                <div class="content-wrapper d-flex align-items-center auth px-0">
                    <div class="row w-100 mx-0">
                        <div class="col-lg-4 mx-auto">
                            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                                <div class="text-center">
                                    <h4>Hãy đổi mật khẩu cho tài khoản của bạn</h4>
                                </div>
                                <form class="pt-3" action="{{route('admin.changepw')}}" method="POST">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <div class="form-group">
                                        <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="Mật khẩu mới" required autofocus minlength="8">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input name="repassword" type="password" class="form-control form-control-lg" id="repassword" placeholder="Xác nhận mật khẩu" required minlength="8">
                                        @error('repassword')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">ĐỔI MẬT KHẨU</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <script src="{{asset('js/settings.js')}}"></script>
    <script src="{{asset('js/todolist.js')}}"></script>
    <!-- endinject -->
</body>


<!-- Mirrored from www.urbanui.com/justdo/template/demo/horizontal-default-light/pages/samples/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 26 Mar 2020 01:31:01 GMT -->

</html>