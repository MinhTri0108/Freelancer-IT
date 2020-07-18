@extends('admin.master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="pb-2 border-bottom mb-3">
                    <h4 class="font-weight-bold ">Danh sách dự án</h4>
                </div>
                <div>
                    <form method="POST" action="{{route('admin.projectmgmt')}}" id="searchForm" name="searchForm">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="action" value="search">
                                <input type="text" class="form-control" placeholder="Nhập tên dự án (Bỏ trống để hiện tất cả)" aria-label="Recipient's username" id="keyword" name="keyword">
                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm kiếm</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(count($projects) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Tên dự án</th>
                                <th>Chủ dự án</th>
                                <th>Mức giá của dự án</th>
                                <th>Tình trạng</th>
                                <th class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>{{$project->name}}</td>
                                <td>{{$project->username}}</td>
                                <td>{{$project->pay_range}}</td>
                                @switch($project->state)
                                @case("recruiting")
                                <td class="font-weight-medium text-warning">Đang tuyển dụng</td>
                                @break
                                @case("in progress")
                                <td class="font-weight-medium text-success">Đang tiến hành</td>
                                @break
                                @default
                                <td class="font-weight-medium text-primary">Đã hoàn thành</td>
                                @endswitch
                                <td class="text-center">
                                    <form action="{{route('admin.projectmgmt')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="project_id" value="{{$project->id}}">
                                        @if($project->state == "recruiting")
                                        <button class="btn btn-danger btn-icon btn-rounded"><i class="ti-trash"></i></button>
                                        @else
                                        <button class="btn btn-danger btn-icon btn-rounded" disabled><i class="ti-trash"></i></button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{$projects->links()}}
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
@endsection
@section('foot-script')
<!-- plugin js for this page -->
<script src="{{asset('/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->

<!-- End custom js for this page-->
@endsection