<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from www.urbanui.com/justdo/template/demo/horizontal-default-light/pages/samples/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 26 Mar 2020 01:31:08 GMT -->

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Đăng ký</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('css/horizontal-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
</head>

<body>
  <!-- <div class="container-scroller"> -->
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel">
        <div class=" d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <h4>Chào mừng bạn đến với Freelancer IT</h4>
                <h6 class="font-weight-light">Hãy đăng ký tài khoản</h6>
                <form class="pt-3" action="{{route('register')}}" method="POST">
                  <input type="hidden" name="_token" value="{{@csrf_token()}}">
                  <div class="form-group">
                    <input name="username" value="{{old('username')}}" type="text" class="form-control form-control-lg @error('username') is-invalid @enderror" id="Username" placeholder="Username" required autofocus>
                    @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <input name="lastname" value="{{old('lastname')}}" type="text" class="form-control form-control-lg @error('lastname') is-invalid @enderror" id="Lastname" placeholder="Họ" required autofocus>
                    @error('lastname')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <input name="firstname" value="{{old('firstname')}}" type="text" class="form-control form-control-lg @error('firstname') is-invalid @enderror" id="Firstname" placeholder="Tên" required autofocus>
                    @error('firstname')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <input name="email" value="{{old('email')}}" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="inputEmail" placeholder="Email" required autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <input name="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="inputPassword" placeholder="Mật khẩu" required>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <input name="repassword" type="password" class="form-control form-control-lg @error('repassword') is-invalid @enderror" id="inputRePassword" placeholder="Xác nhận mật khẩu" required>
                    @error('repassword')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                  
                  <div class="mt-3">
                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">ĐĂNG KÝ</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light">
                    Bạn đã có tài khoản? <a href="{{route('login')}}" class="text-primary">Đăng nhập</a>
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
  <!-- </div> -->
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


<!-- Mirrored from www.urbanui.com/justdo/template/demo/horizontal-default-light/pages/samples/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 26 Mar 2020 01:31:08 GMT -->

</html>