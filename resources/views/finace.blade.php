@extends('master')
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
                        <div class="media">
                            <form class="w-100 forms-sample" name="password" method="POST" action="#" id="depositForm">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <div class="form-group">
                                    <label for="d_money">Số tiền muốn nạp</label>
                                    <input type="number" class="form-control" id="d_money" name="d_money" required>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Nạp tiền</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-pills-withdraw" role="tabpanel" aria-labelledby="v-pills-withdraw-tab">
                        <h4 class="font-weight-bold border-bottom pb-3">Rút tiền từ tài khoản</h4>
                        <div class="media">
                            <form class="w-100 forms-sample" name="password" method="POST" action="#" id="withdrawForm">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="pa_type" value="password">
                                <div class="form-group">
                                    <label for="w_money">Số tiền muốn rút</label>
                                    <input type="number" class="form-control" id="w_money" name="w_money" required minlength="8">
                                </div>
                                <div class="form-group">
                                    <label for="password">Mật khẩu của người dùng</label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Rút tiền</button>
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