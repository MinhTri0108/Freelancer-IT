<?php

use Carbon\Carbon;
?>
@extends('master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3">Dự án gần đây</h4>
                @if(count($projects) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Tên dự án</th>
                                <th>Số người chào giá</th>
                                <th>Giá chào trung bình</th>
                                <th>Kết thúc chào giá</th>
                                <th>Tình trạng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
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
                                @switch($project->state)
                                @case("in progress")
                                <td class="font-weight-medium text-success">Đang tiến hành</td>
                                @break
                                @case("completed")
                                <td class="font-weight-medium text-primary">Đã hoàn thành</td>
                                @break
                                @default
                                <td class="font-weight-medium text-warning">Đang tuyển dụng</td>
                                @endswitch
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3">Hoạt động gần đây</h4>
                @if(count(Auth::user()->notifications) > 0)
                @foreach(Auth::user()->notifications as $notification)
                <?php
                Carbon::setLocale('vi');
                $created_at = $notification->created_at;
                $current_dt = Carbon::now();
                if ($current_dt->diffInDays($created_at) <= 3) {
                    $datetime = $created_at->diffForHumans($current_dt);
                } else {
                    $datetime = $created_at->format('d/m/Y');
                }
                ?>
                <div class="row notif-group">
                    <div class="col-12">
                        <div class="d-flex profile-feed-item">
                            <img src="{{asset('images/logo/logo-100x100.png')}}" alt="profile" class="img-sm rounded" />
                            @switch($notification->data[0]['type'])
                            @case("bidding")
                            <div class="ml-3">
                                @if(Auth::user()->id != $notification->data[0]['freelancer_id'])
                                <p><a href="#">{{$notification->data[0]['freelancer_name']}}</a> đã chào giá cho dự án <a href="{{route('projectdetail',$notification->data[0]['project_id'])}}">{{$notification->data[0]['project_name']}}</a> của bạn</p>
                                @else
                                <p>Bạn đã chào giá cho dự án <a href="{{route('projectdetail',$notification->data[0]['project_id'])}}">{{$notification->data[0]['project_name']}}</a></p>
                                @endif
                                <p>{{$datetime}}</p>
                            </div>
                            @break
                            @case("newproject")
                            <div class="ml-3">
                                <p>Dự án <a href="{{route('projectdetail',$notification->data[0]['project_id'])}}">{{$notification->data[0]['project_name']}}</a> của bạn đã được đăng</p>
                                <p>{{$datetime}}</p>
                            </div>
                            @break
                            @case("chosen")
                            <div class="ml-3">
                                @if(Auth::user()->id != $notification->data[0]['freelancer_id'])
                                <p>Bạn đã chọn freelancer <a href="#">{{$notification->data[0]['freelancer_name']}}</a> và bắt đầu dự án <a href="{{route('projectdetail',$notification->data[0]['project_id'])}}">{{$notification->data[0]['project_name']}}</a> của bạn</p>
                                @else
                                <p>Bạn đã được chọn để thực hiện dự án <a href="{{route('projectdetail',$notification->data[0]['project_id'])}}">{{$notification->data[0]['project_name']}}</a></p>
                                @endif
                                <p>{{$datetime}}</p>
                            </div>
                            @break
                            @case("completed")
                            <div class="ml-3">
                                <p><a href="#">{{$notification->data[0]['freelancer_name']}}</a> đã hoàn thành 1 cột mốc của dự án <a href="{{route('projectdetail',$notification->data[0]['project_id'])}}">{{$notification->data[0]['project_name']}}</a></p>
                                <p>{{$datetime}}</p>
                            </div>
                            @break
                            @case("paid")
                            <div class="ml-3">
                                <p>Cột mốc <i>{{$notification->data[0]['ms_name']}}</i> thuộc dự án <a href="{{route('projectdetail',$notification->data[0]['project_id'])}}">{{$notification->data[0]['project_name']}}</a> đã được thanh toán</p>
                                <p>{{$datetime}}</p>
                            </div>
                            @break
                            @endswitch
                        </div>
                    </div>
                </div>
                @if($loop->count == 10)
                @break
                @endif
                @endforeach
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Chưa có hoạt động nào</h4>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection