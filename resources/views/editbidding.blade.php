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
                <a href="#" class="badge badge-outline-primary">{{$skill->skillname}}</a>
                @endforeach
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="row pb-2 border-bottom">
                    <div class="col-12">
                        <h4 class="font-weight-bold">Chỉnh sửa thông tin chào giá</h4>
                    </div>
                </div>
                <p class="pb-2 pt-3">Bạn được phép chỉnh sửa thông tin chào giá cho tới khi có freelancer được nhận</p>
                <h5 class="font-weight-bold">Chi tiết đặt giá</h5>
                <form class="forms-sample" name="bidding" method="POST" action="{{route('editbidding',$bidding->id)}}" id="biddingForm">
                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bid_amount" class="font-weight-bold">Giá chào</label>
                                <div class="input-group" id="bidGroup">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-dark">$</span>
                                    </div>
                                    <input type="number" class="form-control" id="bid_amount" name="bid_amount" required min="{{$price_array[0]}}" value="{{$bidding->bid_amount}}" data-fixedfee="{{$fee->fixed_fee}}" data-feerate="{{$fee->fee_rate}}" data-fixedboundary="{{$fee->fixed_boundary}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-dark">USD</span>
                                    </div>
                                </div>
                                @if($bidding->bid_amount > $fee->fixed_boundary)
                                <p class="mt-1" id="receive">Số tiền thực lãnh: ${{($bidding->bid_amount * (100 - $fee->fee_rate)) / 100}}</p>
                                @else
                                <p class="mt-1" id="receive">Số tiền thực lãnh: ${{$bidding->bid_amount - $fee->fixed_fee}}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period" class="font-weight-bold">Thời gian hoàn thành dự án</label>
                                <div class="input-group" id="periodGroup">
                                    <input type="number" class="form-control" id="period" required min="1" max="1000" name="period" value="{{$bidding->period}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text text-dark">Ngày</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="proposal" class="font-weight-bold">Đề xuất của bạn dành cho dự án</label>
                        <textarea class="form-control" maxlength="4000" id="proposal" rows="8" placeholder="Điều gì khiến bạn tự tin rằng mình phù hợp với dự án này" required minlength="10" name="proposal">{{$bidding->proposal}}</textarea>
                    </div>
                    <p class="font-weight-bold">Hãy định ra các cột mốc để thực hiện dự án</p>
                    <div id="milestoneList">
                        @foreach($bidding->milestones as $milestone)
                        @if($loop->first)
                        <div class="milestoneGroup row">
                            <div class="col-md-7 form-group">
                                <input type="text" class="form-control" placeholder="Tên của cột mốc" id="milestone_name_0" required name="milestone_name[]" value="{{$milestone->ms_name}}">
                            </div>
                            <div class="col-md-3 col-10 form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-dark">$</span>
                                    </div>
                                    <input type="number" class="form-control m-price" id="milestone_price_0" name="milestone_price[]" required min="5" value="{{$milestone->ms_price}}">
                                </div>
                            </div>
                            <div class="col-md-1 col-1 grid-margin">
                                <button type="button" class="btn btn-success btn-icon btn-rounded" id="addMilestone" disabled>+</button>
                            </div>
                        </div>
                        @else
                        <div class="milestoneGroup row">
                            <div class="col-md-7 form-group">
                                <input type="text" class="form-control" placeholder="Tên của cột mốc" id="milestone_name_{{$milestone->id - 1}}" required name="milestone_name[]" value="{{$milestone->ms_name}}">
                            </div>
                            <div class="col-md-3 col-10 form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text text-dark">$</span>
                                    </div>
                                    <input type="number" class="form-control m-price" id="milestone_price_{{$milestone->id - 1}}" name="milestone_price[]" required min="5" value="{{$milestone->ms_price}}">
                                </div>
                            </div>
                            <div class="col-md-1 col-1 grid-margin">
                                <button type="button" class="btn btn-danger btn-icon btn-rounded delMilestone">-</button>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    <div id="alert-mess" class="alert alert-danger" style="display: none;">Tổng giá trị các cột mốc phải bằng giá chào</div>
                    <div class="text-right pt-3">                                            
                        <button type="submit" class="btn btn-primary btn-md">Lưu</button>                                                
                        <a href="{{url()->previous()}}" class="btn btn-secondary btn-md">Hủy</a>         
                    </div>
                </form>
            </div>
        </div>
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
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="{{asset('/js/form-addons.js')}}"></script>
<!-- <script src="{{asset('/js/form-validation.js')}}"></script> -->
<script src="{{asset('/js/bt-maxLength.js')}}"></script>
<script src="{{asset('/js/typeahead.js')}}"></script>
<script>
    $(function() {

        var i = $('.m-price').length;

        $('#addMilestone').on('click', function() {
            newMilestone(i++);
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
                if (bidamount > fixedboundary) {
                    $('#receive').html('Số tiền thực lãnh: $' + ((bidamount * (100 - feerate)) / 100));
                } else {
                    $('#receive').html('Số tiền thực lãnh: $' + (bidamount - fixedfee));
                }
                checkPrice();
            }
        });
    })
</script>
<!-- End custom js for this page-->
@endsection