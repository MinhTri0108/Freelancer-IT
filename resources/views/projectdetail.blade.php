@extends('master')
@section('head-css')
<link rel="stylesheet" href="{{asset('/vendors/jquery-tags-input/jquery.tagsinput.min.css')}}">
@endsection
@section('content-wrapper')
<div class="row">
    <div class="col-md-9 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row pb-2 border-bottom">
                    <div class="col-8">
                        <h4 class="font-weight-bold">{{$project->name}}</h4>
                    </div>
                    <div class="col-4">
                        <h4 class="font-weight-bold text-right">{{$project->pay_range}}</h4>
                    </div>
                </div>
                <p class="pb-2 pt-3">{{$project->description}}</p>
                <h5 class="font-weight-bold">Các kỹ năng cần thiết</h5>
                @foreach ($project->skills_required as $skill)
                <p class="badge badge-outline-primary mb-0">{{$skill->skillname}}</p>
                @endforeach
                @if((is_null($project->freelancer_id)) && (Auth::user()->id == $project->user_id))
                <div class="d-flex pt-3">                                                                                            
                    <button type="button" class="btn btn-secondary btn-md" id="del-pro" data-proid="{{$project->id}}">Xóa dự án</button>
                    <p class="align-self-center m-0 pl-2">(Chỉ có thể xóa các dự án chưa có người nhận)</p>        
                </div>
                @endif
            </div>
        </div>
        @if(Auth::user()->id != $project->user_id)
            @if($had_bidded == 0)     
            <div class="card mt-3">
                <div class="card-body">
                    @if(!is_null($project->freelancer_id))
                    <h4 class="font-weight-bold pt-2 text-center">Đã có người đảm nhận dự án</h4>
                    @else
                        @if($is_closed == true)
                        <h4 class="font-weight-bold pt-2 text-center">Đã hết thời hạn chào giá</h4>
                        @else
                        <div class="row pb-2 border-bottom">
                            <div class="col-12">
                                <h4 class="font-weight-bold">Chào giá cho dự án này</h4>
                            </div>
                        </div>
                        <p class="pb-2 pt-3">Bạn được phép chỉnh sửa thông tin chào giá cho tới khi có freelancer được nhận</p>
                        <h5 class="font-weight-bold">Chi tiết đặt giá</h5>
                        <form class="forms-sample" name="bidding" method="POST" action="{{route('projectdetail',$project->id)}}" id="biddingForm">
                            <input type="hidden" name="_token" value="{{@csrf_token()}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bid_amount" class="font-weight-bold">Giá chào</label>
                                        <div class="input-group" id="bidGroup">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-dark">$</span>
                                            </div>
                                            <input type="number" class="form-control" id="bid_amount" name="bid_amount" required min="{{$price_array[0]}}" value="{{$avg_price}}" data-fixedfee="{{$fee->fixed_fee}}" data-feerate="{{$fee->fee_rate}}" data-fixedboundary="{{$fee->fixed_boundary}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text text-dark">USD</span>
                                            </div>
                                        </div>
                                        @if($avg_price > $fee->fixed_boundary)
                                        <p class="mt-1" id="receive">Số tiền thực lãnh: ${{($avg_price * (100 - $fee->fee_rate)) / 100}}</p>
                                        @else
                                        <p class="mt-1" id="receive">Số tiền thực lãnh: ${{$avg_price - $fee->fixed_fee}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="period" class="font-weight-bold">Thời gian hoàn thành dự án</label>
                                        <div class="input-group" id="periodGroup">
                                            <input type="number" class="form-control" id="period" required min="1" max="1000" name="period" value="7">
                                            <div class="input-group-append">
                                                <span class="input-group-text text-dark">Ngày</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="proposal" class="font-weight-bold">Hãy đưa ra đề xuất của bạn dành cho dự án</label>
                                <textarea class="form-control" maxlength="4000" id="proposal" rows="8" placeholder="Điều gì khiến bạn tự tin rằng mình phù hợp với dự án này" required minlength="10" name="proposal"></textarea>
                            </div>
                            <h5 class="font-weight-bold">Hãy định ra các cột mốc để thực hiện dự án</h5>
                            <div id="milestoneList">
                                <div class="milestoneGroup row">
                                    <div class="col-md-7 form-group">
                                        <input type="text" class="form-control" placeholder="Tên của cột mốc" id="milestone_name_0" required name="milestone_name[]" value="Tên cột mốc">
                                    </div>
                                    <div class="col-md-3 col-10 form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-dark">$</span>
                                            </div>
                                            <input type="number" class="form-control m-price" id="milestone_price_0" name="milestone_price[]" required min="5" value="{{$avg_price}}">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-1 grid-margin">
                                        <button type="button" class="btn btn-success btn-icon btn-rounded" id="addMilestone" disabled>+</button>
                                    </div>
                                </div>
                            </div>
                            <div id="alert-mess" class="alert alert-danger" style="display: none;">Tổng giá trị các cột mốc phải bằng giá chào</div>
                            <button type="submit" class="btn btn-primary mr-2" id="btnSubmit">Chào giá</button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
            @else
                @if(!is_null($project->freelancer_id))               
                    @if(Auth::user()->id != $project->freelancer_id)
                    <div class="card mt-3">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Đã có người đảm nhận dự án</h4>
                        </div>
                    </div>
                    @else
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row pb-2 border-bottom">
                                <div class="col-12">
                                    <h4 class="font-weight-bold">Tình hình của dự án</h4>
                                </div>
                            </div>
                            @if($project->ended_at == 0)
                            <p class="pt-3"><b>Thời hạn thực hiện dự án: </b>Đã hết hạn</p>
                            @else
                            <p class="pt-3"><b>Thời hạn thực hiện dự án: </b>Còn {{$project->ended_at}} ngày</p>
                            @endif         
                            <p class="font-weight-bold ">Danh sách các cột mốc:</p>
                            <div class="col-md-12 col-sm-12 align-content-center">
                            <table class="table table-borderless table-responsive" id="ms-table">
                                <tbody>
                                    @foreach($biddings->milestones as $milestone)
                                    <tr>
                                        <td>{{$milestone->ms_name}}</td>
                                        <td>${{$milestone->ms_price}} USD</td>
                                        @if($milestone->is_completed == true)
                                            @if($milestone->is_late == true)
                                            <td class="text-warning font-weight-bold">Đã hoàn thành - quá hạn</td>
                                            @else
                                            <td class="text-primary font-weight-bold">Đã hoàn thành - đúng hạn</td>
                                            @endif
                                            @if($milestone->is_paid == true)
                                            <td class="text-primary font-weight-bold text-center">Đã thanh toán</td>
                                            @else
                                            <td class="text-danger font-weight-bold text-center">Chưa thanh toán</td>
                                            @endif
                                        @else
                                        <td class="text-success font-weight-bold" id="cmpl_{{$milestone->id}}">Đang tiến hành</td>
                                        <td class="text-center text-danger font-weight-bold"><button class="btn btn-success complete-btn" type="button" data-bidid="{{$biddings->id}}" data-msid="{{$milestone->id}}" id="bid_{{$milestone->id}}">Xác nhận hoàn thành</button></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                            <div id="alert-mess" class="alert alert-danger text-center mt-3" style="display: none;">Số tiền trong tài khoản không đủ để thanh toán, vui lòng nạp thêm tiền vào tài khoản</div>
                        </div>
                    </div>
                    @endif
                @else
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row pb-2 border-bottom">
                            <div class="col-12">
                                <h4 class="font-weight-bold">Thông tin chào giá của bạn</h4>
                            </div>
                        </div>
                        <div class="pt-3">
                                <div class="row">
                                    <div class="col-md-6">                                                                      
                                        @if($biddings->bid_amount > $fee->fixed_boundary)
                                        <p><b>Giá chào: </b>${{$biddings->bid_amount}} USD (Thực lãnh: ${{($biddings->bid_amount * (100 - $fee->fee_rate)) / 100}})</p>
                                        @else
                                        <p><b>Giá chào: </b>${{$biddings->bid_amount}} USD (Thực lãnh: ${{$biddings->bid_amount - $fee->fixed_fee}})</p>            
                                        @endif                                  
                                    </div>
                                    <div class="col-md-6">
                                        <p><b>Thời gian hoàn thành dự án: </b>{{$biddings->period}} Ngày</p>
                                    </div>
                                </div>
                                
                                <p class="font-weight-bold">Đề xuất của bạn dành cho dự án:</p>
                                <p>{{$biddings->proposal}}</p>
                                
                                <p class="font-weight-bold">Danh sách các cột mốc:</p>
                                <div class="table-responsive col-md-4 col-sm-10">
                                    <table class="table table-borderless table-sm" id="ms-table">
                                        <thead>
                                            <tr>
                                            <th scope="col">Tên cột mốc</th>
                                            <th scope="col">Giá trị</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($biddings->milestones as $milestone)
                                            <tr>
                                                <td>{{$milestone->ms_name}}</td>
                                                <td>${{$milestone->ms_price}} USD</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                        <div class="text-right pt-3">                                            
                            <a href="{{route('editbidding',$biddings->id)}}" class="btn btn-primary btn-md">Chỉnh sửa</a>                                                
                            <button type="button" class="btn btn-secondary btn-md" id="delete-bid" data-bidid="{{$biddings->id}}">Hủy chào giá</button>        
                        </div>
                    </div>
                </div>
                @endif
            @endif
        @else
            @if(is_null($project->freelancer_id))
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row pb-2 border-bottom">
                        <div class="col-12">
                            <h4 class="font-weight-bold">Danh sách freelancer đã chào giá</h4>
                        </div>
                    </div>
                    @if(count($biddings) != 0)
                    @foreach($biddings as $bidding)
                    <div class="row py-4 border-bottom">
                        <div class="col-8">                   
                            <div class="d-flex align-items-center">
                                <img src="{{asset('images/avatar/'.$bidding->avatar)}}" class="img-sm" alt="profile" />
                                <div class="ml-3">
                                    <p class="mb-0 font-weight-bold">{{$bidding->username}}</p>
                                </div>
                            </div>                                       
                            <p class="my-2">{{$bidding->proposal}}</p>
                            <p>Email: <b>{{$bidding->email}}</b></p>
                        </div>
                        <div class="col-4 text-right">
                            <h4 class="font-weight-bold mb-4">${{$bidding->bid_amount}} USD</h4>                        
                            <!-- <button type="button" class="btn btn-secondary btn-md">Chat</button>                                                             -->
                            <button type="button" class="btn btn-primary btn-md choose-btn" data-toggle="modal" data-target="#milestonesModal" name="{{$bidding->id}}">Chọn</button>
                        </div>
                    </div>
                    @endforeach
                    <div class="modal fade" id="milestonesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="milestonesModalLabel">Các cột mốc freelancer đặt ra cho dự án</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive" id="milestones-table">
                                    <!-- <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                            <th>Tên cột mốc</th>
                                            <th>Giá trị</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>Jacob</td>
                                            <td>53275531</td>
                                            </tr>
                                        </tbody>
                                    </table> -->
                                </div>              
                            </div>
                            <div class="modal-footer">
                                <form class="forms-sample" name="choose" method="POST" action="{{route('choosefl')}}" id="chooseForm">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}" hidden>
                                    <input type="text" id="project_id" name="project_id" value="{{$project->id}}" hidden>
                                    <input type="text" id="freelancer_id" name="freelancer_id" hidden>
                                    <input type="text" id="period2" name="period2" hidden>
                                    <button type="submit" class="btn btn-success" id="agree-btn">Đồng ý</button>
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                    @else
                    <h4 class="font-weight-bold pt-4 text-center">Chưa có người chào giá cho dự án</h4>
                    @endif
                </div>
            </div>
            @else
            <div class="card mt-3">
                <div class="card-body">
                    <div class="row pb-2 border-bottom">
                        <div class="col-12">
                            <h4 class="font-weight-bold">Tình hình của dự án</h4>
                        </div>
                    </div>                   
                    <!-- <p class="pt-3"><b>Người thực hiện: </b><a href="#">{{$biddings->username}}</a></p>
                    <p><b>Số tiền chào giá: </b>${{$biddings->bid_amount}} USD</p> -->
                    @if($project->ended_at == 0)
                    <p class="pt-3"><b>Thời hạn thực hiện dự án: </b>Đã hết hạn</p>
                    @else
                    <p class="pt-3"><b>Thời hạn thực hiện dự án: </b>Còn {{$project->ended_at}} ngày</p>
                    @endif 
                    <p class="font-weight-bold">Danh sách các cột mốc:</p>
                    <div class="col-md-12 col-sm-12 align-content-center">
                    <table class="table table-borderless table-responsive" id="ms-table">
                        <tbody>
                            @foreach($biddings->milestones as $milestone)
                            <tr>
                                <td>{{$milestone->ms_name}}</td>
                                <td>${{$milestone->ms_price}} USD</td>
                                @if($milestone->is_completed == true)
                                    @if($milestone->is_late == true)
                                    <td class="text-warning font-weight-bold">Đã hoàn thành - quá hạn</td>
                                    @else
                                    <td class="text-primary font-weight-bold">Đã hoàn thành - đúng hạn</td>
                                    @endif
                                    @if($milestone->is_paid == true)
                                    <td class="text-primary font-weight-bold text-center">Đã thanh toán</td>
                                    @else
                                    <td class="text-primary font-weight-bold text-center"><button class="btn btn-success pay-btn" type="button" data-bidid="{{$biddings->id}}" data-msid="{{$milestone->id}}" id="bid_{{$milestone->id}}">Thanh toán</button></td>
                                    @endif
                                @else
                                <td class="text-success font-weight-bold">Đang tiến hành</td>
                                <td class="text-center"><button class="btn btn-success pay-btn" type="button" data-bidid="{{$biddings->id}}" data-msid="{{$milestone->id}}" disabled>Thanh toán</button></td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    <div id="alert-mess" class="alert alert-danger text-center mt-3" style="display: none;">Số tiền trong tài khoản không đủ để thanh toán, vui lòng nạp thêm tiền vào tài khoản</div>
                </div>
            </div>
            @endif
        @endif
    </div>
    <div class="col-md-3 grid-margin">
        <div class="card">
            <div class="card-body">
                @if((Auth::user()->id == $project->user_id && !is_null($project->freelancer_id)) || (Auth::user()->id == $project->freelancer_id && !is_null($project->freelancer_id)))
                <div class="row pb-2 border-bottom">
                    <h4 class="font-weight-bold col-12">Chi tiết đặt giá dự án</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Người đảm nhận:</td>
                                <td class="text-muted px-0"><a href="#">{{$biddings->username}}</a></td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Số tiền chào giá:</td>
                                <td class="text-muted px-0 ">${{$biddings->bid_amount}} USD</td>
                            </tr>         
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Số tiền thực lãnh:</td>
                                @if($biddings->bid_amount > $fee->fixed_boundary)
                                <td class="text-muted px-0 ">${{($biddings->bid_amount * (100 - $fee->fee_rate)) / 100}} USD</td>
                                @else
                                <td class="text-muted px-0 ">${{$biddings->bid_amount - $fee->fixed_fee}} USD</td>            
                                @endif   
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <div class="row pb-2 border-bottom">
                    <h4 class="font-weight-bold col-12">Chi tiết đặt giá dự án</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Số người đặt giá:</td>
                                <td class="text-muted px-0">{{$project->bid}}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pl-0 pr-1 font-weight-bold">Giá thầu trung bình:</td>
                                <td class="text-muted px-0 ">${{$project->average_bid}} USD</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('foot-script')
<!-- plugin js for this page -->
<script src="{{asset('/vendors/jquery-tags-input/jquery.tagsinput.min.js')}}"></script>
<!-- <script src="{{asset('/vendors/jquery-validation/jquery.validate.min.js')}}"></script> -->
<script src="{{asset('/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('/vendors/typeahead.js/typeahead.bundle.min.js')}}"></script>
<script src="{{asset('/vendors/sweetalert/sweetalert.min.js')}}"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="{{asset('/js/form-addons.js')}}"></script>
<!-- <script src="{{asset('/js/alerts.js')}}"></script> -->
<!-- <script src="{{asset('/js/form-validation.js')}}"></script> -->
<script src="{{asset('/js/bt-maxLength.js')}}"></script>
<script src="{{asset('/js/typeahead.js')}}"></script>
<script>
    $(function(){
        
        $('#milestonesModal').on('show.bs.modal', function(event) {

            var button = $(event.relatedTarget) // Button that triggered the modal
            var name = button.attr("name");
            var current_url = window.location.href;
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('milestone')}}",
                type: "post",
                dataType: "json",
                data: {
                    b_id: name,
                    current_url: current_url
                },
                success: function(data) {
                    $('#milestones-table').append(data.milestones);
                    $('#freelancer_id').val(data.userid);
                    $('#period2').val(data.period);
                },
                error: function() {
                    alert('Sever error');
                }
            });
        })

        $('#milestonesModal').on('hidden.bs.modal', function(event) {
            $('#milestones-table').empty();
        })

        var i = 1;

        $('#addMilestone').on('click', function() {
            newMilestone(i++);
        });

        $('body').on('click', '.pay-btn', function(event) {
            swal({
                title: 'Xác nhận thanh toán',
                text: "Hành động này sẽ trừ tiền trong tài khoản của bạn!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3f51b5',
                cancelButtonColor: '#ff4081',
                confirmButtonText: 'Great ',
                buttons: {
                    confirm: {
                        text: "Đồng ý",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true
                    },
                    cancel: {
                        text: "Hủy",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    }
                }
            })
            .then((pay)=>{
                if(pay){
                    var bidid = $(this).data('bidid');
                    var msid = $(this).data('msid');
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('pay')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            bidid: bidid,
                            msid: msid
                        },
                        success: function(data) {
                            if(data.needbalances){
                                $('#alert-mess').show();
                            }
                            if(data.success)
                                $('#bid_'+msid).replaceWith("Đã thanh toán");
                            else
                                alert('Thất bại');
                        },
                        error: function() {
                            alert('Sever error');
                        }
                    });
                }
            });
        });

        $('body').on('click', '.complete-btn', function(event) {
            swal({
                title: 'Xác nhận hoàn thành dự án',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3f51b5',
                cancelButtonColor: '#ff4081',
                confirmButtonText: 'Great ',
                buttons: {
                    confirm: {
                        text: "Đồng ý",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true
                    },
                    cancel: {
                        text: "Hủy",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    }
                }
            })
            .then((completed)=>{
                if(completed){
                    var bid_id = $(this).data('bidid');
                    var ms_id = $(this).data('msid');   
                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    
                    $.ajax({
                        url: "{{route('complete')}}",
                        type: "post",
                        dataType: "json",
                        data: {
                            bidid: bid_id,
                            msid: ms_id
                        },
                        success: function(data) {
                            if(data.success){
                                $('#cmpl_'+ms_id).removeClass("text-success");
                                
                                if(data.late){
                                    $('#cmpl_'+ms_id).addClass("text-warning");
                                    $('#cmpl_'+ms_id).text("Đã hoàn thành - quá hạn");
                                }
                                else{
                                    $('#cmpl_'+ms_id).addClass("text-primary");
                                    $('#cmpl_'+ms_id).text("Đã hoàn thành - đúng hạn");
                                }
                                $('#bid_'+ms_id).replaceWith("Chưa thanh toán");
                            }
                            else
                                alert('Thất bại');
                        },
                        error: function() {
                            alert('Sever error');
                        }
                    });
                }
            });
        });
        
        function newMilestone(i) {
            var sum = 0;
            $('.m-price').each(function() {
                sum += parseFloat($(this).val());
            });
            var x = parseFloat($('#bid_amount').val() - sum);
            var milestone = '<div class="milestoneGroup row">' +
                '<div class="col-md-7 form-group">' +
                '<input type="text" class="form-control" placeholder="Tên của cột mốc" id="milestone_name_' + i + '" required name="milestone_name[]" value="Tên cột mốc">' +
                '</div>' +
                '<div class="col-md-3 col-10 form-group">' +
                '<div class="input-group">' +
                '<div class="input-group-prepend">' +
                '<span class="input-group-text text-dark">$</span>' +
                '</div>' +
                '<input type="number" class="form-control m-price" id="milestone_price_' + i + '" required min="5" name="milestone_price[]" value="' + x + '">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-1 col-1 grid-margin">' +
                '<button type="button" class="btn btn-danger btn-icon btn-rounded delMilestone">-</button>' +
                '</div>' + '</div>';
            $('#milestoneList').append(milestone);
            checkPrice();
        };
        
        $('#milestoneList').on('click', '.delMilestone', function() {
            $(this).parent().parent().remove();
            checkPrice();
        });

        $('#milestoneList').on("keyup change", "input.m-price", function() {
            checkPrice();
        });

        function checkPrice() {
            var sum = 0;
            $('.m-price').each(function() {
                sum += parseFloat($(this).val());
            });
            if (sum > $('#bid_amount').val()) {
                $('#alert-mess').show();
                $('#addMilestone').attr("disabled", true);
            } else {
                $('#alert-mess').hide();
                if (sum == $('#bid_amount').val()) {
                    $('#addMilestone').attr("disabled", true);
                } else {
                    $('#addMilestone').attr("disabled", false);
                }
            }
        };

        $('#biddingForm').submit(function() {
            var sum = 0;
            $('.m-price').each(function() {
                sum += parseFloat($(this).val());
            });
            if (sum < $('#bid_amount').val()) {
                $('#alert-mess').show();
                return false;
            }
        });

        $('#bid_amount').on("keyup change", function() {
            var bidamount = $('#bid_amount').val();
            var min = parseFloat($('#bid_amount').attr('min'));
            var fixedfee = $('#bid_amount').data('fixedfee');
            var feerate = $('#bid_amount').data('feerate');
            var fixedboundary = $('#bid_amount').data('fixedboundary');
            if (bidamount && bidamount >= min) {
                $('#milestone_price_0').val(bidamount);
                if(bidamount > fixedboundary)
                {
                    $('#receive').html('Số tiền thực lãnh: $' + ((bidamount * (100 - feerate)) / 100));
                }
                else{
                    $('#receive').html('Số tiền thực lãnh: $' + (bidamount - fixedfee));
                }
                checkPrice();
            }
        });

    })
</script>
@if((Auth::user()->id != $project->user_id) && ($had_bidded != 0) && is_null($project->freelancer_id))
<script>
    $(function(){
        $('#delete-bid').on('click',function(){
            swal({
                title: 'Bạn có chắc muốn hủy chào giá?',
                text: "Bạn sẽ không thể khôi phục được các thông tin này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3f51b5',
                cancelButtonColor: '#ff4081',
                confirmButtonText: 'Great ',
                buttons: {
                    confirm: {
                        text: "Đồng ý",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true
                    },
                    cancel: {
                        text: "Hủy",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    }
                }
            })
            .then((willDelete)=>{
                if(willDelete){
                    window.location.href = "{{route('deletebidding',$biddings->id)}}";
                }
            });
        });
    })
</script>
@endif

@if((Auth::user()->id == $project->user_id) && is_null($project->freelancer_id))
<script>
    $(function(){
        $('#del-pro').on('click',function(){
            swal({
                title: 'Bạn có chắc muốn xóa dự án?',
                text: "Bạn sẽ không thể khôi phục được dự án này!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3f51b5',
                cancelButtonColor: '#ff4081',
                confirmButtonText: 'Great ',
                buttons: {
                    confirm: {
                        text: "Đồng ý",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true
                    },
                    cancel: {
                        text: "Hủy",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    }
                }
            })
            .then((willDelete)=>{
                if(willDelete){
                    window.location.href = "{{route('deleteproject',$project->id)}}";
                }
            });
        });
    })
</script>
@endif
<!-- End custom js for this page-->
@endsection