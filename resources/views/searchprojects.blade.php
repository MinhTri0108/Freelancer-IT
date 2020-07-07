@extends('master')
@section('head-css')
<link rel="stylesheet" href="{{asset('/vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
@endsection
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3">Tìm dự án</h4>
                <form name="searchp" method="POST" action="{{route('searchp')}}" id="searchpForm">
                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                    <input id="keyword" name="keyword" type="text" class="form-control mb-2" placeholder="Vui lòng nhập tên dự án cần tìm">

                    <select class="js-example-basic-multiple form-control" style="width: 100%;" multiple="multiple" name="skills[]">
                        @foreach($skills_list as $skill)
                        <option value="{{$skill->id}}">{{$skill->skillname}}</option>
                        @endforeach
                    </select>
                    <div class="text-right mt-2">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>

                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="pb-2 border-bottom">
                    <h4 class="font-weight-bold">Danh sách dự án</h4>
                </div>
                @if(count($projects)>0)
                @foreach($projects as $project)
                <div class="py-4 border-bottom">
                    <div class="row">
                        <div class="col-7">
                            <h5 class="font-weight-bold"><a href="{{route('projectdetail',$project->id)}}" style="text-decoration: none;">{{$project->name}}</a></h5>
                        </div>
                        <div class="col-5">
                            <h5 class="font-weight-bold text-right">{{$project->pay_range}}</h5>
                        </div>
                    </div>
                    <p class="pb-2 pt-2">{{$project->description}}</p>
                    <h5 class="text-success font-weight-bold">Chưa có người đảm nhận</h5>
                    @if($project->close_date == 0)
                    <p>Đã hết hạn chào giá</p>
                    @else
                    <p>Còn <b>{{$project->close_date}} ngày</b> trước khi đóng chào giá - có <b>{{$project->bid}}</b> người chào giá</p>
                    @endif
                    <div class="d-flex">
                        <h5 class="font-weight-bold m-0 align-self-center">Kỹ năng yêu cầu:</h5>
                        @foreach ($project->skills_required as $skill)
                        <a href="#" class="badge badge-outline-primary ml-2">{{$skill->skillname}}</a>
                        @endforeach
                    </div>
                </div>
                @endforeach
                {{$projects->links()}}
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Không tìm thấy dự án phù hợp</h4>
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
<script src="{{asset('/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('/js/select2.js')}}"></script>
@endsection