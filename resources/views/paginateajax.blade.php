<table class="table table-striped table-borderless">
    <thead>
        <tr>
            <th>Tên dự án</th>
            <th>Số người đấu thầu</th>
            <th>Giá thầu trung bình</th>
            <th>Kết thúc đấu thầu</th>
            <th>Tình trạng</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $project)
        <tr>
            <td><a href="{{route('projectdetail',$project->id)}}">{{$project->name}}</a></td>
            <td class="font-weight-bold">{{$project->bid}}</td>
            <td>{{$project->average_bid}}</td>
            <td>
                @if($project->close_date == 0)
                Đã hết hạn
                @else
                Còn {{$project->close_date}} ngày
                @endif
            </td>
            <td class="font-weight-medium text-success">{{$project->state}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-4">
{!! $projects->render() !!}
</div>