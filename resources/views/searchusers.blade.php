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
                <h4 class="font-weight-bold border-bottom pb-3">Tìm người dùng</h4>
                <form name="searchp" method="POST" action="{{route('searchu')}}" id="searchpForm">
                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                    <input id="keyword" name="keyword" type="text" class="form-control mb-2" placeholder="Vui lòng nhập tên người dùng cần tìm">
                    <select class="js-example-basic-multiple form-control" style="width: 100%;" multiple="multiple" name="skills[]">
                        @foreach($skills_list as $skill)
                        <option value="{{$skill->id}}">{{$skill->skillname}} ({{$skill->jobs}})</option>
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
                    <h4 class="font-weight-bold">Danh sách người dùng</h4>
                </div>
                @if(count($users)>0)
                @foreach($users as $user)
                <div class="d-flex py-4 border-bottom">
                    <div>
                        <img src="{{asset('images/avatar/'.$user->avatar)}}" style="width: 110px; height: 110px;" alt="profile" />
                    </div>
                    <div class="pl-4">
                        <h4><a href="{{route('profile',$user->id)}}">{{$user->last_name}} {{$user->first_name}} ({{$user->username}})</a></h4>
                        @if(is_null($user->introduce))
                        <p>N/A</p>
                        @else
                        <p>{{$user->introduce}}</p>
                        @endif
                        <p>Email: <b>{{$user->email}}</b></p>
                        <div class="d-flex">
                            <h5 class="font-weight-bold m-0 align-self-center">Kỹ năng cá nhân:</h5>
                            @if(count($user->skills)>0)
                            @foreach ($user->skills as $skill)
                            <a href="#" class="badge badge-outline-primary ml-2">{{$skill->skillname}}</a>
                            @endforeach
                            @else
                            <p class="m-0 align-self-center ml-2">N/A</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                {{$users->links()}}
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Không tìm thấy người dùng hợp điều kiện</h4>
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