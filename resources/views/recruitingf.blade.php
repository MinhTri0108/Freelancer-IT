@if(count($projects_f) > 0)
<table class="table table-striped table-borderless">
    <thead>
        <tr>
            <th>Tên dự án</th>
            <th style="width: 17%;">Số người chào giá</th>
            <th style="width: 17%;">Giá chào trung bình</th>
            <th style="width: 17%;">Giá chào của tôi</th>
            <th style="width: 17%;">Thời hạn kết thúc</th>
            <th style="width: 17%;">Tình trạng</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects_f as $project)
        <tr>
            <td><a href="{{route('projectdetail',$project->id)}}">{{$project->name}}</a></td>
            <td class="font-weight-bold">{{$project->bid}}</td>
            <td>${{$project->average_bid}} USD</td>
            <td>${{$project->bid_amount}}USD</td>
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
<div class="mt-4">
{!! $projects_f->render() !!}
</div>
@else
<div class="stretch-card">
    <div class="card bg-light">
        <div class="card-body">
            <h4 class="font-weight-bold pt-2 text-center">Chưa có dự án nào</h4>
        </div>
    </div>
</div>
@endif