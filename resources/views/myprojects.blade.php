@extends('master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <div class="row pb-2 border-bottom mb-3">
                    <div class="col-8 align-self-center">
                        <h4 class="font-weight-bold m-0">Dự án của tôi</h4>
                    </div>
                    <div class="col-4 text-right">
                        <select name="u_type" id="u_type" class="form-control-sm">
                            @if(Auth::user()->is_freelancer)
                            <option value="1" selected>Freelancer</option>
                            <option value="2">Nhà tuyển dụng</option>
                            @else
                            <option value="1">Freelancer</option>
                            <option value="2" selected>Nhà tuyển dụng</option>
                            @endif
                        </select>
                    </div>
                </div>
                <!-- <p class="card-title mb-0">Dự án của tôi</p> -->
                <ul class="nav nav-pills nav-pills-primary" id="pills-tab-e" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-recuriting-tab" data-toggle="pill" href="#pills-recuriting" role="tab" aria-controls="pills-recuriting" aria-selected="true">Dự án đang mở chào giá</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-inprogress-tab" data-toggle="pill" href="#pills-inprogress" role="tab" aria-controls="pills-inprogress" aria-selected="false">Dự án đang tiến hành</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-completed-tab" data-toggle="pill" href="#pills-completed" role="tab" aria-controls="pills-completed" aria-selected="false">Dự án đã hoàn thành</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent-e">
                    <div class="tab-pane fade show active" id="pills-recuriting" role="tabpanel" aria-labelledby="pills-recuriting-tab">
                        <div class="media">
                            <div class="table-responsive" id="paginate_1">
                                @if(count($projects_e) > 0)
                                @include('recruiting')
                                @else
                                <div class="stretch-card">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="font-weight-bold pt-2 text-center">Chưa có dự án nào</h4>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-inprogress" role="tabpanel" aria-labelledby="pills-inprogress-tab">
                        <div class="media">
                            <div class="table-responsive" id="paginate_2">
                                @if(count($projects_e) > 0)
                                @include('recruiting')
                                @else
                                <div class="stretch-card">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="font-weight-bold pt-2 text-center">Chưa có dự án nào</h4>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-completed" role="tabpanel" aria-labelledby="pills-completed-tab">
                        <div class="media">
                            <div class="table-responsive" id="paginate_3">
                                @if(count($projects_e) > 0)
                                @include('recruiting')
                                @else
                                <div class="stretch-card">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="font-weight-bold pt-2 text-center">Chưa có dự án nào</h4>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="nav nav-pills nav-pills-primary" id="pills-tab-f" role="tablist" style="display: none;">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-recuritingf-tab" data-toggle="pill" href="#pills-recuritingf" role="tab" aria-controls="pills-recuritingf" aria-selected="true">Dự án tham gia chào giá</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-inprogressf-tab" data-toggle="pill" href="#pills-inprogressf" role="tab" aria-controls="pills-inprogressf" aria-selected="false">Dự án đang tiến hành</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-completedf-tab" data-toggle="pill" href="#pills-completedf" role="tab" aria-controls="pills-completedf" aria-selected="false">Dự án đã hoàn thành</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent-f" style="display: none;">
                    <div class="tab-pane fade show active" id="pills-recuritingf" role="tabpanel" aria-labelledby="pills-recuritingf-tab">
                        <div class="media">
                            <div class="table-responsive" id="paginate_4">
                                @if(count($projects_f) > 0)
                                @include('recruitingf')
                                @else
                                <div class="stretch-card">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="font-weight-bold pt-2 text-center">Chưa có dự án nào</h4>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-inprogressf" role="tabpanel" aria-labelledby="pills-inprogressf-tab">
                        <div class="media">
                            <div class="table-responsive" id="paginate_5">
                                @if(count($projects_f) > 0)
                                @include('recruitingf')
                                @else
                                <div class="stretch-card">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="font-weight-bold pt-2 text-center">Chưa có dự án nào</h4>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-completedf" role="tabpanel" aria-labelledby="pills-completedf-tab">
                        <div class="media">
                            <div class="table-responsive" id="paginate_6">
                                @if(count($projects_f) > 0)
                                @include('recruitingf')
                                @else
                                <div class="stretch-card">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h4 class="font-weight-bold pt-2 text-center">Chưa có dự án nào</h4>
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
    </div>
</div>
@endsection
@section('foot-script')
<script type="text/javascript">
    window.onload = function() {
        var opval = $('#u_type option:selected').val();
        if (opval == 1) {
            $('#pills-tab-f').show();
            $('#pills-tabContent-f').show();
            $('#pills-tab-e').hide();
            $('#pills-tabContent-e').hide();
        } else {
            $('#pills-tab-f').hide();
            $('#pills-tabContent-f').hide();
            $('#pills-tab-e').show();
            $('#pills-tabContent-e').show();
        }
    }
    var type_p = "recruiting"
    $(function() {
        $('.pagination').addClass('mb-0')

        $('#pills-recuriting-tab').on('click', function(event) {
            type_p = "recruiting";
            getPaginateServer(1);
        });

        $('#pills-inprogress-tab').on('click', function(event) {
            type_p = "inprogress";
            getPaginateServer(1);
        });

        $('#pills-completed-tab').on('click', function(event) {
            type_p = "completed";
            getPaginateServer(1);
        });

        $('#pills-recuritingf-tab').on('click', function(event) {
            type_p = "recruitingf";
            getPaginateServer(1);
        });

        $('#pills-inprogressf-tab').on('click', function(event) {
            type_p = "inprogressf";
            getPaginateServer(1);
        });

        $('#pills-completedf-tab').on('click', function(event) {
            type_p = "completedf";
            getPaginateServer(1);
        });

        $('#u_type').on('change', function() {
            var opval = $('#u_type option:selected').val();
            if (opval == 1) {
                $('#pills-tab-f').show();
                $('#pills-tabContent-f').show();
                $('#pills-tab-e').hide();
                $('#pills-tabContent-e').hide();
            } else {
                $('#pills-tab-f').hide();
                $('#pills-tabContent-f').hide();
                $('#pills-tab-e').show();
                $('#pills-tabContent-e').show();
            }
        })

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
                type_p: type_p,
            }
        }).done(function(data) {
            //data lấy trên server về sẽ được đổ vào id #paginate
            if (type_p == "recruiting")
                $("#paginate_1").empty().html(data);
            if (type_p == "inprogress")
                $("#paginate_2").empty().html(data);
            if (type_p == "completed")
                $("#paginate_3").empty().html(data);
            if (type_p == "recruitingf")
                $("#paginate_4").empty().html(data);
            if (type_p == "inprogressf")
                $("#paginate_5").empty().html(data);
            if (type_p == "completedf")
                $("#paginate_6").empty().html(data);
            location.hash = page_current;
            $('.pagination').addClass('mb-0')
        })
    }
</script>
@endsection