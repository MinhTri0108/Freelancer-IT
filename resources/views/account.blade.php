@extends('master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-3 grid-margin">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills nav-pills-vertical nav-pills-info pb-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <li class="nav-item">
                        <a class="nav-link active" id="v-pills-account-tab" data-toggle="pill" href="#v-pills-account" role="tab" aria-controls="v-pills-account" aria-selected="true">
                            Thông tin người dùng
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                            Đặt lại mật khẩu
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="tab-content tab-content-vertical border-0" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-pills-account" role="tabpanel" aria-labelledby="v-pills-account-tab">
                        <h4 class="font-weight-bold border-bottom pb-3">Thông tin tài khoản</h4>
                        <div class="media">
                            <form class="w-100 forms-sample" name="account" method="POST" action="{{route('account',$user->id)}}" id="accountForm">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="pa_type" value="account">
                                <h4>Họ tên</h4>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="last_name">Họ</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{$user->last_name}}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="first_name">Tên</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{$user->first_name}}" required>
                                    </div>
                                </div>
                                <h4>Địa chỉ liên hệ</h4>
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{$user->address}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="city_province">Tỉnh/Thành phố</label>
                                    <input type="text" class="form-control" id="city_province" name="city_province" value="{{$user->city_province}}" required>
                                </div>
                                <h4>Số điện thoại</h4>
                                <div class="form-group row">
                                    <div class="col-md-2 align-self-center">
                                        <label for="phone">Số điện thoại</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{$user->phone}}" required>
                                    </div>
                                </div>
                                <h4>Loại tài khoản</h4>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-check m-0">
                                            @if($user->is_freelancer == 0)
                                            <label for="f_radio" class="form-check-label"><input type="radio" id="f_radio" name="type_acc" value="1" class="form-check-input">Freelancer</label><br>
                                            @else
                                            <label for="f_radio" class="form-check-label"><input type="radio" id="f_radio" name="type_acc" value="1" class="form-check-input" checked>Freelancer</label><br>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check m-0">
                                            @if($user->is_freelancer == 0)
                                            <label for="e_radio" class="form-check-label"><input type="radio" id="e_radio" name="type_acc" value="0" class="form-check-input" checked>Người tuyển dụng</label><br>
                                            @else
                                            <label for="e_radio" class="form-check-label"><input type="radio" id="e_radio" name="type_acc" value="0" class="form-check-input">Người tuyển dụng</label><br>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                        <h4 class="font-weight-bold border-bottom pb-3">Đổi mật khẩu</h4>
                        <div class="media">
                            <form class="w-100 forms-sample" name="password" method="POST" action="{{route('account',$user->id)}}" id="passwordForm">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="pa_type" value="password">
                                <div class="form-group">
                                    <label for="cur_pw">Mật khẩu hiện tại</label>
                                    <input type="password" class="form-control" id="cur_pw" name="cur_pw" required minlength="8">
                                </div>
                                <div class="form-group">
                                    <label for="new_pw">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="new_pw" name="new_pw" required minlength="8">
                                </div>
                                <div class="form-group">
                                    <label for="conf_pw">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" id="conf_pw" name="conf_pw" required minlength="8">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection