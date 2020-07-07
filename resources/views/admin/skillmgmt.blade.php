@extends('admin.master')
@section('content-wrapper')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <div class="row pb-2 border-bottom mb-3">
                    <div class="col-6 align-self-center">
                        <h4 class="font-weight-bold m-0">Danh sách các kỹ năng cho dự án</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary btn-sm" id="add-price" data-toggle="modal" data-target="#addPriceModal">Thêm mới</button>
                    </div>
                </div>
                @if(count($skills))
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Tên kỹ năng</th>
                                <th style="width: 25%;">Tổng số dự án liên quan</th>
                                <th style="width: 25%;" class="text-center">Sửa</th>
                                <th style="width: 25%;" class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($skills as $skill)
                            <tr>
                                <td>{{$skill->skillname}}</td>
                                <td>{{$skill->jobs}}</td>
                                <td class="text-center"><button class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#udSkillModal" data-skillname="{{$skill->skillname}}" data-skillid="{{$skill->id}}" ><i class="ti-pencil-alt"></i></button></td>
                                <td class="text-center">
                                    <form action="{{route('admin.skillmgmt')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                        <input type="hidden" name="skill_type" value="delete">
                                        <input type="hidden" name="skill_id" value="{{$skill->id}}">
                                        @if(count($skills) == 1)
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
                {{$skills->links()}}
                @else
                <div class="stretch-card">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h4 class="font-weight-bold pt-2 text-center">Chưa có kỹ năng nào</h4>
                        </div>
                    </div>
                </div>
                @endif
                <div class="modal fade" id="addPriceModal" tabindex="-1" role="dialog" aria-labelledby="addPriceModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addPriceModalLabel">Thêm mức kỹ năng mới</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addForm" name="addForm" method="POST" action="{{route('admin.skillmgmt')}}">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <input type="hidden" name="skill_type" value="add">
                                    <div class="form-group">
                                        <label for="skillname" class="col-form-label">Tên kỹ năng</label>
                                        <input type="text" name="skillname" class="form-control" id="skillname" placeholder="Tên kỹ năng" required>
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
                <div class="modal fade" id="udSkillModal" tabindex="-1" role="dialog" aria-labelledby="udSkillModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="udSkillModalLabel">Chỉnh sửa tên kỹ năng</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="udForm" name="udForm" method="POST" action="{{route('admin.skillmgmt')}}">
                                    <input type="hidden" name="_token" value="{{@csrf_token()}}">
                                    <input type="hidden" name="skill_type" value="update">
                                    <input type="hidden" name="skill_id" id="ud_id" value="0">
                                    <div class="form-group">
                                        <label for="ud_skillname" class="col-form-label">Tên kỹ năng</label>
                                        <input type="text" name="ud_skillname" class="form-control" id="ud_skillname" placeholder="Tên kỹ năng" required>
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
                skillname: {
                    required: true,
                    minlength: 1,
                    maxlength: 70
                },
            },
            messages: {
                skillname: {
                    required: "Vui lòng nhập tên kỹ năng",
                    minlength: "Tên mức giá cần có ít nhất 1 ký tự",
                    maxlength: "Tên mức giá tối đa dài 70 ký tự"
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
                ud_skillname: {
                    required: true,
                    minlength: 1,
                    maxlength: 70
                },
            },
            messages: {
                ud_skillname: {
                    required: "Vui lòng nhập tên kỹ năng",
                    minlength: "Tên mức giá cần có ít nhất 1 ký tự",
                    maxlength: "Tên mức giá tối đa dài 70 ký tự"
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

        $('#udSkillModal').on('show.bs.modal', function(event) {
            var btn = $(event.relatedTarget);
            $('#ud_skillname').val(btn.data('skillname'));
            $('#ud_id').val(btn.data('skillid'));
        });
    })
</script>
<!-- End custom js for this page-->
@endsection