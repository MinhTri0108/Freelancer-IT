@extends('admin.master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row pb-2 border-bottom mb-3">
                    <div class="col-6 align-self-center">
                        <h4 class="font-weight-bold m-0">Danh sách quản trị viên</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" id="add-admin" data-toggle="modal" data-target="#addAdminModal">Thêm mới</button>
                    </div>
                </div>
                <div>
                    <form method="POST" action="{{route('admin.adminmgmt')}}" id="searchForm" name="searchForm">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="admin_type" value="search">
                                <input type="text" class="form-control" placeholder="Nhập tên đăng nhập hoặc họ tên Admin (Bỏ trống để hiện tất cả Admin)" aria-label="Recipient's username" id="keyword" name="keyword">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(count($admins) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Tên đăng nhập</th>
                                <th>Họ tên QTV</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Phân quyền</th>
                                <th class="text-center">Sửa</th>
                                <th class="text-center">Đặt lại mật khẩu</th>
                                <th class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td>{{$admin->username}}</td>
                                <td>{{$admin->full_name}}</td>
                                <td>{{$admin->email}}</td>
                                <td>{{$admin->phone}}</td>
                                <td>Cấp {{$admin->level}}</td>
                                <td class="text-center"><button class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#udAdminModal" data-adminid="{{$admin->id}}" data-username="{{$admin->username}}" data-fullname="{{$admin->full_name}}" data-email="{{$admin->email}}" data-phone="{{$admin->phone}}" data-level="{{$admin->level}}"><i class="ti-pencil-alt"></i></button></td>
                                <td class="text-center">
                                    <form action="{{route('admin.adminmgmt')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                        <input type="hidden" name="admin_type" value="resetpass">
                                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                                        <button class="btn btn-primary btn-rounded">Đặt lại</button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form action="{{route('admin.adminmgmt')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                        <input type="hidden" name="admin_type" value="delete">
                                        <input type="hidden" name="admin_id" value="{{$admin->id}}">
                                        @if(Auth::guard('admin')->user()->id == $admin->id)
                                        <button class="btn btn-danger btn-icon btn-rounded" disabled><i class="ti-trash"></i></button>
                                        @else
                                        <button class="btn btn-danger btn-icon btn-rounded"><i class="ti-trash"></i></button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{$admins->links()}}
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Chưa có quản trị viên nào</h4>
                        </div>
                    </div>
                </div>
                @endif
                <div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="addAdminModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addAdminModalLabel">Thêm quản trị viên mới</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addForm" name="addForm" method="POST" action="{{route('admin.adminmgmt')}}">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <input type="hidden" name="admin_type" value="add">
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="username" class="col-form-label">Tên đăng nhập</label>
                                            <input type="text" name="username" class="form-control" id="username" placeholder="Username" required>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="fullname" class="col-form-label">Họ tên admin</label>
                                            <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Họ tên đầy đủ của admin" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="email" class="col-form-label">Email</label>
                                            <input type="text" name="email" class="form-control" id="email" placeholder="Email" required>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="phone" class="col-form-label">Số điện thoại</label>
                                            <input type="text" name="phone" class="form-control" id="phone" placeholder="Số điện thoại" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="level" class="col-form-label">Cấp bậc phân quyền</label>
                                        <input type="number" name="level" class="form-control" id="level" placeholder="Cấp bậc để phân quyền admin" required min="1" max="5">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="agree-btn" form="addForm">Thêm</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="udAdminModal" tabindex="-1" role="dialog" aria-labelledby="udAdminModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="udAdminModalLabel">Chỉnh sửa thông tin QTV</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="udForm" name="udForm" method="POST" action="{{route('admin.adminmgmt')}}">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <input type="hidden" name="admin_type" value="update">
                                    <input type="hidden" name="admin_id" id="ud_id" value="0">
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="username" class="col-form-label">Tên đăng nhập</label>
                                            <h5 id="ud_username" class="font-weight-bold"></h5>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="fullname" class="col-form-label">Họ tên admin</label>
                                            <h5 id="ud_fullname" class="font-weight-bold"></h5>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="ud_email" class="col-form-label">Email</label>
                                            <h5 id="ud_email" class="font-weight-bold"></h5>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="ud_phone" class="col-form-label">Số điện thoại</label>
                                            <input type="text" name="ud_phone" class="form-control" id="ud_phone" placeholder="Số điện thoại" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ud_level" class="col-form-label">Cấp bậc phân quyền</label>
                                        <input type="number" name="ud_level" class="form-control" id="ud_level" placeholder="Cấp bậc để phân quyền admin" required min="1" max="5">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="agree-btn" form="udForm">Lưu</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot-script')
<!-- plugin js for this page -->
<script src="{{asset('/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script>
    $(function() {
        'use strict';
        $('#addForm').validate({
            rules: {
                username: {
                    required: true,
                    minlength: 5,
                    maxlength: 50
                },
                fullname: {
                    required: true,
                    minlength: 5,
                    maxlength: 45,
                },
                phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                },
                email: {
                    required: true,
                    email: true
                },
                level: {
                    required: true,
                    min: 1,
                    max: 5,
                },
            },
            messages: {
                username: {
                    required: "Vui lòng nhập tên đăng nhập",
                    minlength: "Tên đăng nhập cần có ít nhất 5 ký tự",
                    maxlength: "Tên đăng nhập tối đa dài 50 ký tự"
                },
                fullname: {
                    required: "Vui lòng nhập họ tên admin",
                    minlength: "Họ tên admin cần có ít nhất 5 ký tự",
                    maxlength: "Họ tên admin tối đa dài 45 ký tự"
                },
                phone: {
                    required: "Trường này không được bỏ trống",
                    minlength: "Số điện thoại phải có đúng 10 ký tự",
                    maxlength: "Số điện thoại phải có đúng 10 ký tự"
                },
                email: {
                    required: "Trường này không được bỏ trống",
                    email: "Vui lòng nhập đúng định dạng email"
                },
                level: {
                    required: "Cấp độ không được phép bỏ trống",
                    min: "Cấp độ cao nhất là 1",
                    max: "Cấp độ cao nhất là 5",
                },
            },
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

        $('#udForm').validate({
            rules: {
                ud_phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                },
                ud_level: {
                    required: true,
                    min: 1,
                    max: 5,
                },
            },
            messages: {
                ud_phone: {
                    required: "Trường này không được bỏ trống",
                    minlength: "Số điện thoại phải có đúng 10 ký tự",
                    maxlength: "Số điện thoại phải có đúng 10 ký tự"
                },
                ud_level: {
                    required: "Cấp độ không được phép bỏ trống",
                    min: "Cấp độ cao nhất là 1",
                    max: "Cấp độ cao nhất là 5",
                },
            },
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });

        $('#udAdminModal').on('show.bs.modal', function(event) {
            var btn = $(event.relatedTarget);
            $('#ud_username').text(btn.data('username'));
            $('#ud_fullname').text(btn.data('fullname'));
            $('#ud_email').text(btn.data('email'));
            $('#ud_phone').val(btn.data('phone'));
            $('#ud_level').val(btn.data('level'));
            $('#ud_id').val(btn.data('adminid'));
        });
    })
</script>
<!-- End custom js for this page-->
@endsection