@extends('admin.master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row pb-2 border-bottom mb-3">
                    <div class="col-6 align-self-center">
                        <h4 class="font-weight-bold m-0">Danh sách các mức giá cho dự án</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" id="add-price" data-toggle="modal" data-target="#addPriceModal">Thêm mới</button>
                    </div>
                </div>
                @if(count($prices) > 0)
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Tên mức giá</th>
                                <th>Từ</th>
                                <th>Đến</th>
                                <th class="text-center">Sửa</th>
                                <th class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prices as $price)
                            <tr>
                                <td>{{$price->name}}</td>
                                <td>${{$price->from}} USD</td>
                                @if($price->to == 0)
                                <td>Vô cùng</td>
                                @else
                                <td>${{$price->to}} USD</td>
                                @endif
                                <td class="text-center"><button class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#udPriceModal" data-name="{{$price->name}}" data-from="{{$price->from}}" data-to="{{$price->to}}" data-priceid="{{$price->id}}" ><i class="ti-pencil-alt"></i></button></td>
                                <td class="text-center">
                                    <form action="{{route('admin.pricemgmt')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                        <input type="hidden" name="price_type" value="delete">
                                        <input type="hidden" name="price_id" value="{{$price->id}}">
                                        @if(count($prices) == 1)
                                        <button class="btn btn-danger btn-icon btn-rounded" disabled><i class="ti-trash"></i></button>
                                        @else
                                        <button class="btn btn-danger btn-icon btn-rounded"><i class="ti-trash"></i></button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{$prices->links()}}
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Chưa có mức giá nào</h4>
                        </div>
                    </div>
                </div>
                @endif
                <div class="modal fade" id="addPriceModal" tabindex="-1" role="dialog" aria-labelledby="addPriceModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addPriceModalLabel">Thêm mức giá mới</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addForm" name="addForm" method="POST" action="{{route('admin.pricemgmt')}}">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <input type="hidden" name="price_type" value="add">
                                    <div class="form-group">
                                        <label for="price_name" class="col-form-label">Tên mức giá</label>
                                        <input type="text" name="price_name" class="form-control" id="price_name" placeholder="Tên mức giá" required>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="from" class="col-form-label">Khởi điểm của mức giá</label>
                                            <input type="number" name="from" class="form-control" id="from" placeholder="Khởi điểm" required>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="to" class="col-form-label">Giá trị tối đa của mức giá</label>
                                            <input type="number" name="to" class="form-control" id="to" placeholder="Giá trị tối đa" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="agree-btn" form="addForm">Thêm</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="udPriceModal" tabindex="-1" role="dialog" aria-labelledby="udPriceModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="udPriceModalLabel">Chỉnh sửa mức giá</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="udForm" name="udForm" method="POST" action="{{route('admin.pricemgmt')}}">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <input type="hidden" name="price_type" value="update">
                                    <input type="hidden" name="price_id" id="ud_id" value="0">
                                    <div class="form-group">
                                        <label for="ud_price_name" class="col-form-label">Tên mức giá</label>
                                        <input type="text" name="ud_price_name" class="form-control" id="ud_price_name" placeholder="Tên mức giá" required>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 col-md-6">
                                            <label for="ud_from" class="col-form-label">Khởi điểm của mức giá</label>
                                            <input type="text" name="ud_from" class="form-control" id="ud_from" placeholder="Khởi điểm" required>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <label for="ud_to" class="col-form-label">Giá trị tối đa của mức giá</label>
                                            <input type="text" name="ud_to" class="form-control" id="ud_to" placeholder="Giá trị tối đa" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="agree-btn" form="udForm">Lưu</button>
                                <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            </div>
                        </div>
                    </div>
                </div>
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
        $('#addForm').validate({
            rules: {
                price_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 45
                },
                to: {
                    required: true,
                    min:0,
                },
                from: {
                    required: true,
                    min: 10,
                },
            },
            messages: {
                price_name: {
                    required: "Vui lòng nhập tên mức giá",
                    minlength: "Tên mức giá cần có ít nhất 2 ký tự",
                    maxlength: "Tên mức giá tối đa dài 45 ký tự"
                },
                from: {
                    required: "Trường này không được bỏ trống",
                    min: "Giá khởi điểm ít nhất phải bằng 10",
                },
                to: {
                    required: "Trường này không được bỏ trống",
                    min:"Giá tối đa từ 0 trở lên, 0 có nghĩa là vô cùng",
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

        $('#udForm').validate({
            rules: {
                ud_price_name: {
                    required: true,
                    minlength: 2,
                    maxlength: 45
                },
                ud_to: {
                    required: true,
                    min:0,
                },
                ud_from: {
                    required: true,
                    min: 10,
                },
            },
            messages: {
                ud_price_name: {
                    required: "Vui lòng nhập tên mức giá",
                    minlength: "Tên mức giá cần có ít nhất 2 ký tự",
                    maxlength: "Tên mức giá tối đa dài 45 ký tự"
                },
                ud_from: {
                    required: "Trường này không được bỏ trống",
                    min: "Giá khởi điểm ít nhất phải bằng 10",
                },
                ud_to: {
                    required: "Trường này không được bỏ trống",
                    min:"Giá tối đa từ 0 trở lên, 0 có nghĩa là vô cùng",
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

        $('#udPriceModal').on('show.bs.modal', function(event) {
            var btn = $(event.relatedTarget);
            $('#ud_price_name').val(btn.data('name'));
            $('#ud_from').val(btn.data('from'));
            $('#ud_to').val(btn.data('to'));
            $('#ud_id').val(btn.data('priceid'));
        });
    })
</script>
<!-- End custom js for this page-->
@endsection