@extends('admin.master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="pb-2 border-bottom mb-3">
                    <h4 class="font-weight-bold ">Quản lý giao dịch</h4>
                </div>
                <h4>Danh sách các yêu cầu rút tiền</h4>
                <div>
                    <form method="POST" action="{{route('admin.txnmgmt')}}" id="searchForm" name="searchForm">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="action" value="search">
                                <input type="text" class="form-control" placeholder="Nhập username hoặc mã yêu cầu rút tiền (Bỏ trống để hiện tất cả)" aria-label="Recipient's username" id="keyword" name="keyword">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(count($withdraw_req) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Mã yêu cầu</th>
                                <th>Người dùng</th>
                                <th>Tài khoản PayPal</th>
                                <th class="text-right">Số tiền rút</th>
                                <th class="text-right">Số tiền trong tài khoản</th>
                                <th>Thời điểm yêu cầu</th>
                                <th class="text-center">Duyệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdraw_req as $w_req)
                            <tr>
                                <td>{{$w_req->id}}</td>
                                <td>{{$w_req->username}}</td>
                                <td>{{$w_req->paypal_email}}</td>
                                <td class="text-right">{{$w_req->amount}} VND</td>
                                <td class="text-right">{{$w_req->balances}} VND</td>
                                <td>{{$w_req->created_at}}</td>
                                <td class="text-center font-weight-medium text-primary">
                                    @if($w_req->approved_at === 0)
                                    <form action="{{route('admin.txnmgmt')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="withdraw_id" value="{{$w_req->id}}">
                                        @if($w_req->amount < $w_req->balances)
                                            <button class="btn btn-primary">Duyệt</button>
                                            @else
                                            <button class="btn btn-primary" disabled>Duyệt</button>
                                            @endif
                                    </form>
                                    @else
                                    Đã duyệt
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{$withdraw_req->links()}}
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Chưa có yêu cầu rút tiền nào</h4>
                        </div>
                    </div>
                </div>
                @endif
                <h4 class="border-top pt-3">Lịch sử giao dịch</h4>
                <div class="media" style="display: block;">
                    <ul class="nav nav-pills nav-pills-primary" id="pills-tab-e" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-all-tab" data-toggle="pill" href="#pills-all" role="tab" aria-controls="pills-all" aria-selected="true">Tất cả giao dịch</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-in-tab" data-toggle="pill" href="#pills-in" role="tab" aria-controls="pills-in" aria-selected="false">Tiền vào</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-out-tab" data-toggle="pill" href="#pills-out" role="tab" aria-controls="pills-out" aria-selected="false">Tiền ra</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent-e">
                        <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                            <div class="media">
                                <div class="table-responsive" id="paginate_1">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Loại giao dịch</th>
                                                <th style="width: 25%;">Số tiền giao dịch</th>
                                                <th style="width: 25%;">Thông tin chi tiết</th>
                                                <th style="width: 25%;">Thời điểm giao dịch</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>70.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc 1 thuộc dự án Dự án ma nè</td>
                                                <td>1588260244</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>56.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc 2 thuộc dự án Dự án ma nè, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1588260270</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>8.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265030</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>4.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265035</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>4.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265038</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>20.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Dự án mới nè</td>
                                                <td>1594309709</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>250.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Dự án mới nè</td>
                                                <td>1594558560</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-4">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-in" role="tabpanel" aria-labelledby="pills-in-tab">
                            <div class="media">
                                <div class="table-responsive" id="paginate_2">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Loại giao dịch</th>
                                                <th style="width: 25%;">Số tiền giao dịch</th>
                                                <th style="width: 25%;">Thông tin chi tiết</th>
                                                <th style="width: 25%;">Thời điểm giao dịch</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>70.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc 1 thuộc dự án Dự án ma nè</td>
                                                <td>1588260244</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>56.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc 2 thuộc dự án Dự án ma nè, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1588260270</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>8.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265030</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>4.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265035</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>4.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265038</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>20.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Dự án mới nè</td>
                                                <td>1594309709</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>250.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Dự án mới nè</td>
                                                <td>1594558560</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-4">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-out" role="tabpanel" aria-labelledby="pills-out-tab">
                            <div class="media">
                                <div class="table-responsive" id="paginate_3">
                                    <table class="table table-striped table-borderless">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Loại giao dịch</th>
                                                <th style="width: 25%;">Số tiền giao dịch</th>
                                                <th style="width: 25%;">Thông tin chi tiết</th>
                                                <th style="width: 25%;">Thời điểm giao dịch</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>70.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc 1 thuộc dự án Dự án ma nè</td>
                                                <td>1588260244</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>56.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc 2 thuộc dự án Dự án ma nè, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1588260270</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>8.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265030</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>4.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265035</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>4.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Test thông báo, được giảm giá 20% do freelancer hoàn thành trễ hạn</td>
                                                <td>1594265038</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>20.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Dự án mới nè</td>
                                                <td>1594309709</td>
                                            </tr>
                                            <tr>
                                                <td class="text-danger">Tiền ra</td>
                                                <td>250.0000 VND</td>
                                                <td>Thanh toán tiền cho cột mốc Tên cột mốc thuộc dự án Dự án mới nè</td>
                                                <td>1594558560</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mt-4">

                                    </div>
                                </div>
                            </div>
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

<!-- End custom js for this page-->
@endsection