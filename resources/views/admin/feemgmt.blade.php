@extends('admin.master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold border-bottom pb-3">Thông tin về phí cho dự án</h4>
                <form id="feeForm" name="feeForm" method="POST" action="{{route('admin.feemgmt')}}">
                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6">
                            <label for="fixed_fee" class="col-form-label">Phí tham gia dự án cố định:</label>
                            <input type="number" name="fixed_fee" class="form-control" id="fixed_fee" placeholder="Phí cố định" required value="{{$fee->fixed_fee}}" max="{{$min_price / 2}}" min="1">
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="fixed_boundary" class="col-form-label">Áp dụng phí cố định khi mức giá nhỏ hơn hoặc bằng:</label>
                            <input type="number" name="fixed_boundary" class="form-control" id="fixed_boundary" placeholder="Mức giá áp dụng" required value="{{$fee->fixed_boundary}}" min="{{$min_price}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12 col-md-6">
                            <label for="fee_rate" class="col-form-label">Tỉ lệ phí tham gia dự án theo phần trăm</label>
                            <input type="number" name="fee_rate" class="form-control" id="fee_rate" placeholder="Tỉ lệ phần trăm" required value="{{$fee->fee_rate}}" min="1" max="20">
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <label for="penalty_rate" class="col-form-label">Tỉ lệ phí phạt khi hoàn thành dự án quá hạn</label>
                            <input type="number" name="penalty_rate" class="form-control" id="penalty_rate" placeholder="Tỉ lệ phần trăm" required value="{{$fee->penalty_rate}}" min="1" max="40">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" id="agree-btn">Lưu</button>
                    </div>
                </form>
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
<script>
    $(function() {
        'use strict';
        $('#feeForm').validate({
            rules: {
                fixed_fee: {
                    required: true,
                    min: 1,
                    max: {{$min_price / 2}},
                },
                fixed_boundary: {
                    required: true,
                    min: {{$min_price}},
                },
                fee_rate: {
                    required: true,
                    min: 1,
                    max: 20,
                },
                penalty_rate: {
                    required: true,
                    min: 1,
                    max: 40,
                },
            },
            messages: {
                fixed_fee: {
                    required: "Vui lòng nhập phí cố định",
                    min: "Phí cố định phải lớn hơn 0",
                    max: "Phí cố định phải nhỏ hơn hoặc bằng {{$min_price / 2}}",
                },
                fixed_boundary: {
                    required: "Vui lòng nhập mức giá áp dụng",
                    min: "Mức giá áp dụng phải nhỏ hơn hoặc bằng {{$min_price}}",
                },
                fee_rate: {
                    required: "Vui lòng nhập tỉ lệ phí tham gia",
                    min: "Tỉ lệ phí tham gia phải lớn hơn 0",
                    max: "Tỉ lệ phí tham gia phải nhỏ hơn hoặc bằng 20",
                },
                penalty_rate: {
                    required: "Vui lòng nhập tỉ lệ phí phạt",
                    min: "Tỉ lệ phí phạt phải lớn hơn 0",
                    max: "Tỉ lệ phí phạt phải nhỏ hơn hoặc bằng 40",
                },
            },
            errorPlacement: function(label, element) {
                label.addClass('mt-2 text-danger');
                label.insertAfter(element);
            },
            highlight: function(element, errorClass) {
                $(element).parent().addClass('has-danger')
                $(element).addClass('form-control-danger')
            }
        });
    })
</script>
<!-- End custom js for this page-->
@endsection