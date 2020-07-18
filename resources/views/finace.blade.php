@extends('master')
@section('meta')
<meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->
<meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet Explorer compatibility -->
@endsection
@section('content-wrapper')
<div class="row">
    <div class="col-md-3 grid-margin">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills nav-pills-vertical nav-pills-info pb-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <li class="nav-item">
                        <a class="nav-link active" id="v-pills-history-tab" data-toggle="pill" href="#v-pills-history" role="tab" aria-controls="v-pills-history" aria-selected="true">
                            Lịch sử giao dịch
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="v-pills-deposit-tab" data-toggle="pill" href="#v-pills-deposit" role="tab" aria-controls="v-pills-deposit" aria-selected="false">
                            Nạp tiền vào tài khoản
                        </a>
                    </li>
                    <li class="nav-item mb-0">
                        <a class="nav-link" id="v-pills-withdraw-tab" data-toggle="pill" href="#v-pills-withdraw" role="tab" aria-controls="v-pills-withdraw" aria-selected="false">
                            Rút tiền từ tài khoản
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
                    <div class="tab-pane fade show active" id="v-pills-history" role="tabpanel" aria-labelledby="v-pills-history-tab">
                        <h4 class="font-weight-bold border-bottom pb-3">Lịch sử giao dịch</h4>
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
                                            @if(count($history)>0)
                                            @include('trhistory')
                                            @else
                                            <div class="stretch-card">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h4 class="font-weight-bold pt-2 text-center">Chưa có giao dịch</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-in" role="tabpanel" aria-labelledby="pills-in-tab">
                                    <div class="media">
                                        <div class="table-responsive" id="paginate_2">
                                            @if(true)
                                            @include('trhistory')
                                            @else
                                            <div class="stretch-card">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h4 class="font-weight-bold pt-2 text-center">Chưa có giao dịch</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-out" role="tabpanel" aria-labelledby="pills-out-tab">
                                    <div class="media">
                                        <div class="table-responsive" id="paginate_3">
                                            @if(true)
                                            @include('trhistory')
                                            @else
                                            <div class="stretch-card">
                                                <div class="card bg-light">
                                                    <div class="card-body">
                                                        <h4 class="font-weight-bold pt-2 text-center">Chưa có giao dịch</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-deposit" role="tabpanel" aria-labelledby="v-pills-deposit-tab">
                        <h4 class="font-weight-bold border-bottom pb-3">Nạp tiền vào tài khoản</h4>
                        <div class="media" style="display: block;">
                            <p>Phương thức thanh toán</p>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="paypalRadio" id="paypalRadio" value="paypal" checked>
                                    PayPal
                                </label>
                            </div>
                            <form class="w-100 forms-sample" name="password" method="POST" action="{{route('deposit')}}" id="depositForm">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <div class="form-group">
                                    <label for="d_money">Số tiền muốn nạp</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="d_money" name="d_money" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text text-dark">VND</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Nạp tiền</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-withdraw" role="tabpanel" aria-labelledby="v-pills-withdraw-tab">
                        <h4 class="font-weight-bold border-bottom pb-3">Rút tiền từ tài khoản</h4>
                        <div class="media" style="display: block;">
                            <h5>Các yêu cầu rút tiền:</h5>
                            <div class="mb-3">
                                @if(count($withdraw_req)>0)
                                <table class="table table-striped table-borderless">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%;">Mã yêu cầu</th>
                                            <th style="width: 25%;">Số tiền rút</th>
                                            <th style="width: 25%;">Thời điểm yêu cầu</th>
                                            <th style="width: 25%;">Tình trạng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($withdraw_req as $w_req)
                                        <tr>
                                            <td>{{$w_req->id}}</td>
                                            <td>{{$w_req->amount}} VND</td>
                                            <td>{{$w_req->created_at}}</td>
                                            @if($w_req->approved_at === 0)
                                            <td class="font-weight-medium text-warning">Chưa duyệt</td>
                                            @else
                                            <td class="font-weight-medium text-primary">Đã duyệt</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $withdraw_req->links() }}
                                @else
                                <div class="stretch-card">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="font-weight-bold pt-2 text-center">Chưa yêu cầu rút tiền</h4>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <h5>Gửi yêu cầu rút tiền:</h5>
                            <form class="w-100 forms-sample" name="password" method="POST" action="{{route('withdraw')}}" id="withdrawForm">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="pa_type" value="password">
                                <div class="form-group">
                                    <label for="w_money">Số tiền muốn rút</label>
                                    <input type="number" class="form-control" id="w_money" name="w_money" required minlength="3">
                                </div>
                                <div class="form-group">
                                    <label for="w_money">Email người dùng đăng ký PayPal</label>
                                    <input type="email" class="form-control" id="w_email" name="w_email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Mật khẩu của người dùng</label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
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
@section('foot-script')
<!-- Add the checkout buttons, set up the order and approve the order -->

<script type="text/javascript">
    var type_t = "all"
    $(function() {
        $('.pagination').addClass('mb-0')

        $('#pills-all-tab').on('click', function(event) {
            type_t = "all";
            getPaginateServer(1);
        });

        $('#pills-in-tab').on('click', function(event) {
            type_t = "in";
            getPaginateServer(1);
        });

        $('#pills-out-tab').on('click', function(event) {
            type_t = "out";
            getPaginateServer(1);
        });


        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            // Tìm trang hiện tại để active
            // $('li').removeClass('active');
            $('.page-item').removeClass('active');

            $(this).parent('li').addClass('active');

            var myurl = $(this).attr('href');
            var page_current = $(this).attr('href').split('page=')[1];
            //Lấy số trang hiện tại và gửi nó lên server sử dung ajax
            getPaginateServer(page_current);
        });
    });

    function getPaginateServer(page_current) {
        $.ajax({
            url: '?page=' + page_current,
            type: "get",
            datatype: "html",
            data: {
                type_t: type_t,
            }
        }).done(function(data) {
            //data lấy trên server về sẽ được đổ vào id #paginate
            if (type_t == "all")
                $("#paginate_1").empty().html(data);
            if (type_t == "in")
                $("#paginate_2").empty().html(data);
            if (type_t == "out")
                $("#paginate_3").empty().html(data);
            location.hash = page_current;
            $('.pagination').addClass('mb-0')
        })
    }
</script>
@endsection