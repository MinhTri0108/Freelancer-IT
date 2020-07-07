@extends('master')
@section('head-css')
<link rel="stylesheet" href="{{asset('/vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
@endsection
@section('content-wrapper')
<div class="row">
    <div class="col-md-9 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <img src="{{asset('images/avatar/'.$user->avatar)}}" style="width: 110px; height: 110px;" alt="profile" />
                    </div>
                    <div class="pl-4">
                        <h4>{{$user->last_name}} {{$user->first_name}}</h4>
                        @if(is_null($user->introduce))
                        @if(Auth::user()->id != $user->id)
                        <p>N/A</p>
                        @else
                        <p>Hãy viết vài dòng để giới thiệu bản thân</p>
                        @endif
                        @else
                        <p>{{$user->introduce}}</p>
                        @endif
                        @if(is_null($user->current_job))
                        @if(Auth::user()->id != $user->id)
                        <p>N/A</p>
                        @else
                        <p>Hãy chia sẽ công việc hiện tại của bạn</p>
                        @endif
                        @else
                        <p>Công việc hiện tại: {{$user->current_job}}</p>
                        @endif
                        <p>Email: <b>{{$user->email}}</b></p>
                    </div>
                </div>
                @if(Auth::user()->id == $user->id)
                <div class="mt-2 row">
                    <div class="col-6">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#avatarModal">Thay đổi hình đại diện</button>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#infoModal">Thay đổi thông tin</button>
                    </div>
                </div>
                <div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-labelledby="avatarModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel-2">Thay đổi ảnh đại diện</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('avatar')}}" method="POST" id="avatarForm" name="avatarForm" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <div class="form-group">
                                        <div class="text-center">
                                            <img id="preview-img" src="{{asset('images/avatar/'.Auth::user()->avatar)}}" style="width: 110px; height: 110px;" class="border" alt="profile" />
                                        </div>
                                        <label>Hãy chọn hình ảnh</label>
                                        <input type="file" name="avatar" class="file-upload-default" id="avatar" accept="image/*" required>
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image" required>
                                            <span class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="file-upload-browse">Chọn hình</button>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" form="avatarForm">Lưu</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="milestonesModalLabel">Thông tin hiện tại</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="infoForm" name="infoForm" method="POST" action="{{route('profile',$user->id)}}">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="ud_type" value="udInfo">
                                <div class="form-group">
                                    <label for="introduce" class="col-form-label">Giới thiệu bản thân:</label>
                                    @if(is_null($user->introduce))
                                    <textarea name="introduce" id="introduce" class="form-control" rows="3" required></textarea>
                                    @else
                                    <textarea name="introduce" id="introduce" class="form-control" rows="3" required>{{$user->introduce}}</textarea>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="current-job" class="col-form-label">Công việc hiện tại:</label>
                                    @if(is_null($user->current_job))
                                    <input type="text" name="current_job" class="form-control" id="current_job" required>
                                    @else
                                    <input type="text" name="current_job" class="form-control" id="current_job" required value="{{$user->current_job}}">
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" form="infoForm" class="btn btn-success">Lưu</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                @if(Auth::user()->id == $user->id)
                <div class="row pb-2 border-bottom mb-3">
                    <div class="col-6 align-self-center">
                        <h4 class="font-weight-bold m-0">Trình độ học vấn</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" id="add-edu">Thêm trường học</button>
                    </div>
                </div>
                @else
                <h4 class="font-weight-bold border-bottom pb-3">Trình độ học vấn</h4>
                @endif
                <div id="edu-info">
                    @if(count($edus) == 0)
                    <p><i>Hiện chưa có trường nào được thêm vào</i></p>
                    @else
                    @foreach($edus as $edu)
                    @if(Auth::user()->id == $user->id)
                    <div class="row mb-2">
                        <div class="col-md-9 col-8 align-self-center">
                            <p>Học tại {{$edu->school_name}} từ năm {{$edu->start_year}} đến {{$edu->end_year}}, trình độ {{$edu->degree}}</p>
                        </div>
                        <div class="col-md-3 col-4 text-right ">
                            <button type="button" class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#eduModal" data-school="{{$edu->school_name}}" data-degree="{{$edu->degree}}" data-syear="{{$edu->start_year}}" data-eyear="{{$edu->end_year}}" data-eduid="{{$edu->id}}"><i class="ti-pencil-alt"></i></button>
                            <button type="button" class="btn btn-danger btn-icon btn-rounded" onclick="window.location.href = '{{route('education.del',$edu->id)}}';"><i class="ti-trash"></i></button>
                        </div>
                    </div>
                    @else
                    <p>Học tại {{$edu->school_name}} từ năm {{$edu->start_year}} đến {{$edu->end_year}}, trình độ {{$edu->degree}}</p>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div id="add-edu-form" style="display: none;">
                    <form id="addeduForm" name="addeduForm" method="POST" action="{{route('education')}}">
                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                        <input type="hidden" name="edu_type" value="add">
                        <div class="form-group">
                            <label for="school_name" class="col-form-label">Tên trường</label>
                            <input type="text" name="school_name" class="form-control" id="school_name" required>
                        </div>
                        <div class="form-group">
                            <label for="degree" class="col-form-label">Trình độ:</label>
                            <input type="text" name="degree" class="form-control" id="degree" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-6">
                                <label for="start_year" class="col-form-label">Từ năm:</label>
                                <select name="start_year" id="start_year" class="form-control">
                                    @for ($i = now()->year; $i >= 1900; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="end_year" class="col-form-label">Đến năm:</label>
                                <select name="end_year" id="end_year" class="form-control">
                                    @for ($i = now()->year; $i >= 1900; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div id="add-edu-alert" class="alert alert-danger text-center mt-3" style="display: none;">Năm kết thúc phải lớn hơn hoặc bằng năm bắt đầu</div>
                        <button type="submit" class="btn btn-success" id="save-edu-btn">Thêm</button>
                        <button type="button" class="btn btn-light" id="cancel-edu-btn">Hủy</button>
                    </form>
                </div>
                <div class="modal fade" id="eduModal" tabindex="-1" role="dialog" aria-labelledby="eduModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="milestonesModalLabel">Chỉnh sửa thông tin trường</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="eduForm" name="eduForm" method="POST" action="{{route('education')}}">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <input type="hidden" name="edu_type" value="edit">
                                    <input type="hidden" name="edu_id" id="edu_id">
                                    <div class="form-group">
                                        <label for="school_name" class="col-form-label">Tên trường</label>
                                        <input type="text" name="ud_school_name" class="form-control" id="ud_school_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="degree" class="col-form-label">Trình độ:</label>
                                        <input type="text" name="ud_degree" class="form-control" id="ud_degree" required>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="from_year" class="col-form-label">Từ năm:</label>
                                            <select name="from_year" id="from_year" class="form-control">
                                                @for ($i = now()->year; $i >= 1900; $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="to_year" class="col-form-label">Đến năm:</label>
                                            <select name="to_year" id="to_year" class="form-control">
                                                @for ($i = now()->year; $i >= 1900; $i--)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <div id="edit-edu-alert" class="alert alert-danger text-center mt-3" style="display: none;">Năm kết thúc phải lớn hơn hoặc bằng năm bắt đầu</div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="agree-btn" form="eduForm">Lưu</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                @if(Auth::user()->id == $user->id)
                <div class="row pb-2 border-bottom mb-3">
                    <div class="col-6 align-self-center">
                        <h4 class="font-weight-bold m-0">Trình độ chuyên môn</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" id="add-qualif">Thêm chứng chỉ hoặc giải thưởng</button>
                    </div>
                </div>
                @else
                <h4 class="font-weight-bold border-bottom pb-3">Trình độ chuyên môn</h4>
                @endif
                <div id="qualif-info">
                    @if(count($qualifes) == 0)
                    <p><i>Hiện chưa có chứng chỉ hoặc giải thưởng nào được thêm vào</i></p>
                    @else
                    @foreach($qualifes as $qualif)
                    @if(Auth::user()->id == $user->id)
                    <div class="row mb-2">
                        <div class="col-md-9 col-8 align-self-center">
                            <p>Nhận được {{$qualif->qualif_name}} do {{$qualif->organization}} cấp vào năm {{$qualif->got_at}}</p>
                        </div>
                        <div class="col-md-3 col-4 text-right ">
                            <button type="button" class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#qualifModal" data-qualif="{{$qualif->qualif_name}}" data-org="{{$qualif->organization}}" data-year="{{$qualif->got_at}}" data-qualifid="{{$qualif->id}}"><i class="ti-pencil-alt"></i></button>
                            <button type="button" class="btn btn-danger btn-icon btn-rounded" onclick="window.location.href = '{{route('qualification.del',$qualif->id)}}';"><i class="ti-trash"></i></button>
                        </div>
                    </div>
                    @else
                    <p>Nhận được {{$qualif->qualif_name}} do {{$qualif->organization}} cấp vào năm {{$qualif->got_at}}</p>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div id="add-qualif-form" style="display: none;">
                    <form id="addqualifForm" name="addqualifForm" method="POST" action="{{route('qualification')}}">
                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                        <input type="hidden" name="qualif_type" value="add">
                        <div class="form-group">
                            <label for="qualif_name" class="col-form-label">Tên chứng chỉ hoặc giải thưởng</label>
                            <input type="text" name="qualif_name" class="form-control" id="qualif_name" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="organization" class="col-form-label">Tên tổ chức cung cấp:</label>
                            <input type="text" name="organization" class="form-control" id="organization" maxlength="50" required>
                        </div>
                        <div class="form-group">
                            <label for="got_at" class="col-form-label">Năm nhận:</label>
                            <select name="got_at" id="got_at" class="form-control">
                                @for ($i = now()->year; $i >= 1900; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success" id="save-qualif-btn">Thêm</button>
                        <button type="button" class="btn btn-light" id="cancel-qualif-btn">Hủy</button>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="qualifModal" tabindex="-1" role="dialog" aria-labelledby="qualifModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="qualifModalLabel">Danh sách chứng chỉ, giải thưởng</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editqualifForm" name="editqualifForm" method="POST" action="{{route('qualification')}}">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="qualif_type" value="edit">
                                <input type="hidden" name="qualif_id" id="qualif_id">
                                <div class="form-group">
                                    <label for="ud_qualif_name" class="col-form-label">Tên chứng chỉ hoặc giải thưởng</label>
                                    <input type="text" name="ud_qualif_name" class="form-control" id="ud_qualif_name" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <label for="ud_organization" class="col-form-label">Tên tổ chức cung cấp:</label>
                                    <input type="text" name="ud_organization" class="form-control" id="ud_organization" maxlength="50" required>
                                </div>
                                <div class="form-group">
                                    <label for="ud_got_at" class="col-form-label">Năm nhận:</label>
                                    <select name="ud_got_at" id="ud_got_at" class="form-control">
                                        @for ($i = now()->year; $i >= 1900; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="agree-btn" form="editqualifForm">Lưu</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                @if(Auth::user()->id == $user->id)
                <div class="row pb-2 border-bottom mb-3">
                    <div class="col-6 align-self-center">
                        <h4 class="font-weight-bold m-0">Kinh nghiệm làm việc</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" id="add-exp">Thêm công việc</button>
                    </div>
                </div>
                @else
                <h4 class="font-weight-bold border-bottom pb-3">Kinh nghiệm làm việc</h4>
                @endif
                <div id="exp-info">
                    @if(count($exps) == 0)
                    <p><i>Hiện chưa có công việc nào được thêm vào</i></p>
                    @else
                    @foreach($exps as $exp)
                    @if(Auth::user()->id == $user->id)
                    <div class="row mb-2">
                        <div class="col-md-9 col-sm-8 align-self-center">
                            @if($exp->cur_working == 1)
                            <p>Giữ vị trí {{$exp->position}} tại {{$exp->company}} từ tháng {{$exp->start_at[1]}}/{{$exp->start_at[0]}} đến hiện tại</p>
                            @else
                            <p>Giữ vị trí {{$exp->position}} tại {{$exp->company}} từ tháng {{$exp->start_at[1]}}/{{$exp->start_at[0]}} đến tháng {{$exp->end_at[1]}}/{{$exp->end_at[0]}}</p>
                            @endif
                        </div>
                        <div class="col-md-3 col-sm-4 text-right">
                            <button type="button" class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#expModal" data-position="{{$exp->position}}" data-company="{{$exp->company}}" data-startat="{{$exp->start_at[0]}}-{{$exp->start_at[1]}}" data-endat="{{$exp->end_at[0]}}-{{$exp->end_at[1]}}" data-expid="{{$exp->id}}" data-curworking="{{$exp->cur_working}}"><i class="ti-pencil-alt"></i></button>
                            <button type="button" class="btn btn-danger btn-icon btn-rounded" onclick="window.location.href = '{{route('experience.del',$exp->id)}}';"><i class="ti-trash"></i></button>
                        </div>
                    </div>
                    @else
                    @if($exp->cur_working == 1)
                    <p>Giữ vị trí {{$exp->position}} tại {{$exp->company}} từ tháng {{$exp->start_at[1]}}/{{$exp->start_at[0]}} đến hiện tại</p>
                    @else
                    <p>Giữ vị trí {{$exp->position}} tại {{$exp->company}} từ tháng {{$exp->start_at[1]}}/{{$exp->start_at[0]}} đến tháng {{$exp->end_at[1]}}/{{$exp->end_at[0]}}</p>
                    @endif
                    @endif
                    @endforeach
                    @endif
                </div>
                <div id="add-exp-form" style="display: none;">
                    <form id="addexpForm" name="addexpForm" method="POST" action="{{route('experience')}}">
                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                        <input type="hidden" name="exp_type" value="add">
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-6">
                                <label for="position" class="col-form-label">Vị trí công việc:</label>
                                <input type="text" name="position" class="form-control" id="position" required>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="company" class="col-form-label">Công ty:</label>
                                <input type="text" name="company" class="form-control" id="company" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 col-md-6">
                                <label for="start_at" class="col-form-label">Từ:</label>
                                <input type="month" name="start_at" class="form-control" id="start_at" required>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label for="end_at" class="col-form-label">Đến:</label>
                                <input type="month" name="end_at" class="form-control" id="end_at" required>
                            </div>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" id="cur_working" name="cur_working">
                                Đang làm việc tại đây
                            </label>
                        </div>
                        <div id="add-exp-alert" class="alert alert-danger text-center mt-3" style="display: none;">Thời gian kết thúc phải lớn hơn hoặc bằng thời gian bắt đầu</div>
                        <button type="submit" class="btn btn-success" id="save-edu-btn">Thêm</button>
                        <button type="button" class="btn btn-light" id="cancel-exp-btn">Hủy</button>
                    </form>
                </div>
            </div>
            <div class="modal fade " id="expModal" tabindex="-1" role="dialog" aria-labelledby="expModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="expModalLabel">Chỉnh sửa công việc</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="expForm" name="expForm" method="POST" action="{{route('experience')}}">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="exp_id" id="exp_id">
                                <input type="hidden" name="exp_type" value="edit">
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-6">
                                        <label for="ud_position" class="col-form-label">Vị trí công việc:</label>
                                        <input type="text" name="ud_position" class="form-control" id="ud_position" required>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label for="ud_company" class="col-form-label">Công ty:</label>
                                        <input type="text" name="ud_company" class="form-control" id="ud_company" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 col-md-6">
                                        <label for="ud_start_at" class="col-form-label">Từ:</label>
                                        <input type="month" name="ud_start_at" class="form-control" id="ud_start_at" required>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <label for="ud_end_at" class="col-form-label">Đến:</label>
                                        <input type="month" name="ud_end_at" class="form-control" id="ud_end_at" required>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" id="ud_cur_working" name="ud_cur_working">
                                        Đang làm việc tại đây
                                    </label>
                                </div>
                                <div id="edit-exp-alert" class="alert alert-danger text-center mt-3" style="display: none;">Thời gian kết thúc phải lớn hơn hoặc bằng thời gian bắt đầu</div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="agree-btn" form="expForm">Lưu</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3 mb-0">Danh sách kỹ năng</h4>
                @if(is_null($user->skills))
                <p class="pt-2"><i>Hiện chưa có kỹ năng nào</i></p>
                @else
                @foreach($user->skills as $skill)
                <p class="badge badge-outline-primary mt-2 mb-0">{{$skill->skillname}}</p>
                @endforeach
                @endif
                @if(Auth::user()->id == $user->id)
                <div class="text-right mt-2">
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#skillModal">Chỉnh sửa kỹ năng</button>
                </div>
                @endif
            </div>
            <div class="modal fade" id="skillModal" tabindex="-1" role="dialog" aria-labelledby="skillModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="skillModalLabel">Danh sách các kỹ năng</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="skillForm" name="infoForm" method="POST" action="{{route('profile',$user->id)}}">
                                <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                <input type="hidden" name="ud_type" value="udSkill">
                                <select class="js-example-basic-multiple w-100 form-control" form="skillForm" multiple="multiple" required name="skill[]" style="width: 100%;">
                                    @foreach($skills_list as $skill)
                                    @if(in_array($skill->id, $skills_arr))
                                    <option value="{{$skill->id}}" selected>{{$skill->skillname}} ({{$skill->jobs}})</option>
                                    @else
                                    <option value="{{$skill->id}}">{{$skill->skillname}} ({{$skill->jobs}})</option>
                                    @endif
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" form="skillForm">Lưu</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3 mb-0">Dự án của người dùng</h4>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Dự án đã đăng:</td>
                                <td class="text-muted px-0">{{$mypj_posted}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Dự án đang thực hiện:</td>
                                <td class="text-muted px-0 ">{{$mypj_inprogress}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Dự án đã hoàn thành:</td>
                                <td class="text-muted px-0 ">{{$mypj_completed}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3 mb-0">Dự án đã tham gia</h4>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Dự án đang tham gia:</td>
                                <td class="text-muted px-0">{{$parpj_inprogress}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Dự án đã hoàn thành:</td>
                                <td class="text-muted px-0 ">{{$parpj_completed}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot-script')
<script src="{{asset('/js/file-upload.js')}}"></script>
<script src="{{asset('/vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('/js/select2.js')}}"></script>
<script>
    window.onload = function() {
        var d = new Date();
        var months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        var max = d.getFullYear() + "-" + months[d.getMonth()];
        $('#end_at').attr("max", max);
    }
    $(function() {
        $('#add-edu').on('click', function() {
            $('#edu-info').hide();
            $('#add-edu-form').show();
        })
        $('#cancel-edu-btn').on('click', function() {
            $('#edu-info').show();
            $('#add-edu-form').hide();
        })

        $('#add-exp').on('click', function() {
            $('#exp-info').hide();
            $('#add-exp-form').show();
        })
        $('#cancel-exp-btn').on('click', function() {
            $('#exp-info').show();
            $('#add-exp-form').hide();
        })

        $('#add-qualif').on('click', function() {
            $('#qualif-info').hide();
            $('#add-qualif-form').show();
        })
        $('#cancel-qualif-btn').on('click', function() {
            $('#qualif-info').show();
            $('#add-qualif-form').hide();
        })

        $('#start_year').change(function(event) {
            checkYear($(this).prop('id'));
        })
        $('#end_year').change(function(event) {
            checkYear($(this).prop('id'));
        })
        $('#from_year').change(function(event) {
            checkYear($(this).prop('id'));
        })
        $('#to_year').change(function(event) {
            checkYear($(this).prop('id'));
        })

        function checkYear(id) {
            if (id == 'start_year' || id == 'end_year') {
                if ($('#start_year').val() > $('#end_year').val()) {
                    $('#add-edu-alert').show();
                } else {
                    $('#add-edu-alert').hide();
                }
            }
            if (id == 'from_year' || id == 'to_year') {
                if ($('#from_year').val() > $('#to_year').val()) {
                    $('#edit-edu-alert').show();
                } else {
                    $('#edit-edu-alert').hide();
                }
            }
        }

        $('#start_at').change(function(event) {
            checkTime($(this).prop('id'));
        })
        $('#end_at').change(function(event) {
            checkTime($(this).prop('id'));
        })
        $('#ud_start_at').change(function(event) {
            checkTime($(this).prop('id'));
        })
        $('#ud_end_at').change(function(event) {
            checkTime($(this).prop('id'));
        })

        function checkTime(id) {
            if (id == 'start_at' || id == 'end_at') {
                var start_at = $('#start_at').val().split('-');
                var end_at = $('#end_at').val().split('-');

                if (start_at[0] > end_at[0]) {
                    $('#add-exp-alert').show();
                } else {
                    if (start_at[0] == end_at[0]) {
                        if (start_at[1] > end_at[1]) {
                            $('#add-exp-alert').show();
                        } else {
                            $('#add-exp-alert').hide();
                        }
                    } else {
                        $('#add-exp-alert').hide();
                    }
                }
            }
            if (id == 'ud_start_at' || id == 'ud_end_at') {
                var start_at = $('#ud_start_at').val().split('-');
                var end_at = $('#ud_end_at').val().split('-');

                if (start_at[0] > end_at[0]) {
                    $('#edit-exp-alert').show();
                } else {
                    if (start_at[0] == end_at[0]) {
                        if (start_at[1] > end_at[1]) {
                            $('#edit-exp-alert').show();
                        } else {
                            $('#edit-exp-alert').hide();
                        }
                    } else {
                        $('#edit-exp-alert').hide();
                    }
                }
            }
        }

        $('#eduModal').on('show.bs.modal', function(event) {
            var btn = $(event.relatedTarget);
            $('#ud_school_name').val(btn.data('school'));
            $('#ud_degree').val(btn.data('degree'));
            $('#from_year').val(btn.data('syear'));
            $('#to_year').val(btn.data('eyear'));
            $('#edu_id').val(btn.data('eduid'));
        });

        $('#expModal').on('show.bs.modal', function(event) {
            var btn = $(event.relatedTarget);
            $('#ud_position').val(btn.data('position'));
            $('#ud_company').val(btn.data('company'));
            $('#ud_start_at').val(btn.data('startat'));
            $('#ud_end_at').val(btn.data('endat'));
            if (btn.data('curworking') == 1) {
                $('#ud_cur_working').prop('checked', true);
            } else {
                $('#ud_cur_working').prop('checked', false);
            }
            $('#exp_id').val(btn.data('expid'));
        });

        $('#qualifModal').on('show.bs.modal', function(event) {
            var btn = $(event.relatedTarget);
            $('#ud_qualif_name').val(btn.data('qualif'));
            $('#ud_organization').val(btn.data('org'));
            $('#ud_got_at').val(btn.data('year'));
            $('#qualif_id').val(btn.data('qualifid'));
        });

        $('#avatarForm').submit(function() {
            var input = document.getElementById('avatar');
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var size = input.files[0].size;
                if (size > 1048576) {
                    $('#avatarForm').append('<div id="alert-ava" class="alert alert-danger">Size ảnh không được vượt quá 1MB</div>')
                    return false;
                } else {
                    return true;
                }
            }
        });
    });
</script>
@endsection