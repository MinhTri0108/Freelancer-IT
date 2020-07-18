@extends('admin.master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="pb-2 border-bottom mb-3">
                    <h4 class="font-weight-bold ">Danh sách người dùng</h4>
                </div>
                <div>
                    <form method="POST" action="{{route('admin.usermgmt')}}" id="searchForm" name="searchForm">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="action" value="search">
                                <input type="text" class="form-control" placeholder="Nhập username hoặc họ tên người dùng (Bỏ trống để hiện tất cả)" aria-label="Recipient's username" id="keyword" name="keyword">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(count($users) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Họ tên người dùng</th>
                                <th class="text-center">Dự án đã đăng</th>
                                <th class="text-center">Dự án đã tham gia</th>
                                <th class="text-center">Số tiền hiện có</th>
                                <th class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                @if(!is_null($user->phone))
                                <td>{{$user->phone}}</td>
                                @else
                                <td>Không có</td>
                                @endif
                                <td>{{$user->last_name}} {{$user->first_name}}</td>
                                <td class="text-center">{{$user->projects_e}}</td>
                                <td class="text-center">{{$user->projects_f}}</td>
                                <td class="text-right">{{floor($user->balances)}} VND</td>
                                <td class="text-center">
                                    <form action="{{route('admin.usermgmt')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                        @if($user->projects_e == 0 && $user->projects_f == 0 && $user->balances == 0)
                                        <button class="btn btn-danger btn-icon btn-rounded"><i class="ti-trash"></i></button>
                                        @else
                                        <button class="btn btn-danger btn-icon btn-rounded" disabled><i class="ti-trash"></i></button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{$users->links()}}
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Chưa có người dùng nào</h4>
                        </div>
                    </div>
                </div>
                @endif
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

<!-- End custom js for this page-->
@endsection