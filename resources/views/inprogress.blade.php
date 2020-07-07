@if(count($projects_e) > 0)
<table class="table table-striped table-borderless">
    <thead>
        <tr>
            <th>Tên dự án</th>
            <th style="width: 17%;">Tên freelancer</th>
            <th style="width: 17%;">Giá chào</th>
            <th style="width: 17%;">Hạn chót</th>
            <th style="width: 17%;">Tình trạng</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects_e as $project)
        <tr>
            <td><a href="{{route('projectdetail',$project->id)}}">{{$project->name}}</a></td>
            <td><a href="{{route('profile',$project->freelancer_id)}}">{{$project->username}}</a></td>
            <td>${{$project->bid_amount}} USD</td>
            <td>
                @if($project->deadline == 0)
                Đã hết hạn
                @else
                Còn {{$project->deadline}} ngày
                @endif
            </td>
            <td class="font-weight-medium text-success">{{$project->state}}</td>
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