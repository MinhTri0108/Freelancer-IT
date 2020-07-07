@extends('master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3">Dự án của tôi</h4>
                <!-- <p class="card-title mb-0">Dự án của tôi</p> -->
                <div class="table-responsive" id="paginate_1">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Tên dự án</th>
                                <th>Số người chào giá</th>
                                <th>Giá chào trung bình</th>
                                <th>Thời hạn kết thúc</th>
                                <th>Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td><a href="{{route('projectdetail',$project->id)}}">{{$project->name}}</a></td>
                                <td class="font-weight-bold">{{$project->bid}}</td>
                                <td>${{$project->average_bid}} USD</td>
                                <td>
                                    @if($project->close_date == 0)
                                    Đã hết hạn
                                    @else
                                    Còn {{$project->close_date}} ngày
                                    @endif
                                </td>
                                <td class="font-weight-medium text-warning">{{$project->state}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{$projects->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot-script')
<script type="text/javascript">
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
            location.hash = page_current;
            $('.pagination').addClass('mb-0')
        })
    }
</script>
@endsection