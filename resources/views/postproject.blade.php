@extends('master')
@section('head-css')
<link rel="stylesheet" href="{{asset('/vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">
@endsection
@section('content-wrapper')
<!-- <div class="row"> -->
<!-- <div class="col-12 grid-margin stretch-card"> -->
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Tạo dự án mới</h4>
        <form class="forms-sample" name="project" method="POST" action="{{route('postproject')}}" id="projectForm">
            <input type="hidden" name="_token" value="{{@csrf_token()}}">
            <div class="form-group">
                <label for="projectname"">Tên dự án</label>
                <input type=" text" maxlength="100" class="form-control" id="projectname" placeholder="Tên dự án" name="projectname">
            </div>
            <div class="form-group">
                <label for="description">Mô tả dự án</label>
                <textarea class="form-control" maxlength="4000" id="description" rows="4" placeholder="Mô tả dự án" name="description"></textarea>
            </div>
            <div class="form-group">
                <label>Các tệp đính kèm</label>
                <input type="file" name="img[]" class="file-upload-default">
                <div class="input-group col-xs-12">
                    <input type="text" class="form-control file-upload-info" disabled placeholder="Tệp đính kèm">
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-primary" type="button">Đăng tải</button>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="tags">Kỹ năng cần thiết</label>
                <select class="js-example-basic-multiple form-control" style="width: 100%;" multiple="multiple" name="skills[]" id="skills" required>
                    @foreach($skills_list as $skill)
                    <option value="{{$skill->id}}">{{$skill->skillname}}</option>
                    @endforeach
                </select>
            </div>
            <!-- <p>Hình thức chi trả</p>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card bg-primary" onclick="showFixedPrice()">
                        <div class="card-body">
                            <p class="card-title text-md-center text-xl-left text-light">Trả theo giá cố định</p>
                            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                                <p class="text-md-center text-xl-left text-light">Hai bên đồng ý với giá tiền được đưa ra từ ban đầu và thanh toán sau khi công việc hoàn tất. Phù hợp với những công việc chỉ làm một lần.</p>
                                <i class="ti-calendar icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card bg-primary" onclick="showbyHour()">
                        <div class="card-body">
                            <p class="card-title text-md-center text-xl-left text-light">Trả tiền theo giờ</p>
                            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                                <p class="text-md-center text-xl-left text-light">Tiền được thanh toán theo giờ dựa trên giá tiền công mỗi giờ được quy định từ trước. Phù hợp với các công việc làm lâu dài.</p>
                                <i class="ti-user icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- <div class="form-group" hidden>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="payment" id="optionsRadios1" value="0">
                        Fixed Price
                    </label>
                </div>
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="payment" id="optionsRadios2" value="1">
                        By Hour
                    </label>
                </div>
            </div> -->
            <div class="form-group" id="budget">
                <label for="exampleSelectGender">Ước lượng ngân sách</label>
                <select class="form-control" id="fixedPrice" name="fixedprice" onchange="customSelected()">
                    @foreach($fixed_prices as $fixed_price)
                    @if($fixed_price->to == 0)
                    <option value="{{$fixed_price->from}}_{{$fixed_price->to}}">Dự án {{$fixed_price->name}} ({{$fixed_price->from}}+ USD)</option>
                    @else
                    <option value="{{$fixed_price->from}}_{{$fixed_price->to}}">Dữ án {{$fixed_price->name}} ({{$fixed_price->from}} - {{$fixed_price->to}} USD)</option>
                    @endif
                    @endforeach
                    <option value="customPrice" id="opCustom">Tùy chọn</option>
                </select>
            </div>
            <div class="form-group row" style="display: none;" id="customBudget">
                <label for="exampleInputUsername2" class="col-sm-1 col-form-label">Từ:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" id="customFrom" name="customFrom" value="10">
                </div>
                <label for="exampleInputUsername2" class="col-sm-1 col-form-label">Đến:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" id="customTo" name="customTo"" value="11">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mr-2" id="btnSubmit">Đăng dự án</button>
            <button class="btn btn-light">Cancel</button>
        </form>
    </div>
</div>
<!-- </div> -->
<!-- </div> -->
@endsection
@section('foot-script')
<script>
    // function showFixedPrice() {
    //     $("#budget").show();
    //     $("#fixedPrice").show();
    //     $("#byHour").hide();
    //     $("#optionsRadios1").prop('checked', true);
    //     $("#btnSubmit").prop('disabled', false);
    // }

    // function showbyHour() {
    //     // $("#budget").show();
    //     // $("#fixedPrice").hide();
    //     // $("#byHour").show();
    //     // $("#optionsRadios2").prop('checked', true);
    //     // $("#btnSubmit").prop('disabled', false);
    //     $("#budget").show();
    //     $("#fixedPrice").show();
    //     $("#byHour").hide();
    //     $("#optionsRadios1").prop('checked', true);
    //     $("#btnSubmit").prop('disabled', false);
    // }

    function customSelected() {
        if ($("#opCustom").is(':selected')) {
            $("#customBudget").show();
        } else {
            $("#customBudget").hide();
        }
    }
</script>
<!-- plugin js for this page -->
<script src="{{asset('/vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('/vendors/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('/vendors/select2/select2.min.js')}}"></script>
<!-- End plugin js for this page -->
<!-- Custom js for this page-->
<script src="{{asset('/js/form-addons.js')}}"></script>
<script src="{{asset('/js/form-validation.js')}}"></script>
<script src="{{asset('/js/bt-maxLength.js')}}"></script>
<script src="{{asset('/js/select2.js')}}"></script>
<!-- End custom js for this page-->
@endsection