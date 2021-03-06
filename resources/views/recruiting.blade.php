@if(count($projects_e) > 0)
<table class="table table-striped table-borderless">
    <thead>
        <tr>
            <th>Tên dự án</th>
            <th style="width: 17%;">Số người chào giá</th>
            <th style="width: 17%;">Giá chào trung bình</th>
            <th style="width: 17%;">Thời hạn kết thúc</th>
            <th style="width: 17%;">Tình trạng</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects_e as $project)
        <tr>
            <td><a href="{{route('projectdetail',$project->id)}}">{{$project->name}}</a></td>
            <td class="font-weight-bold">{{$project->bid}}</td>
            <td>{{$project->average_bid}} VND</td>
            <td>
                @if($project->close_date == 0)
                Đã hết hạn
                @else
                Còn {{$project->close_date}} ngày
                @endif
            </td>
            <td class="font-weight-medium text-warning">Đang tuyển dụng</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-4">
    {!! $projects_e->render() !!}
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