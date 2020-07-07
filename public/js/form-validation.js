(function ($) {
    "use strict";
    // $.validator.setDefaults({
    //   submitHandler: function() {
    //     alert("submitted!");
    //   }
    // });
    $(function () {
        // validate the comment form when it is submitted
        $("#commentForm").validate({
            errorPlacement: function (label, element) {
                label.addClass("mt-2 text-danger");
                label.insertAfter(element);
            },
            highlight: function (element, errorClass) {
                $(element).parent().addClass("has-danger");
                $(element).addClass("form-control-danger");
            },
        });
        // validate signup form on keyup and submit
        $("#signupForm").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                username: {
                    required: true,
                    minlength: 2,
                },
                password: {
                    required: true,
                    minlength: 5,
                },
                confirm_password: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password",
                },
                email: {
                    required: true,
                    email: true,
                },
                topic: {
                    required: "#newsletter:checked",
                    minlength: 2,
                },
                agree: "required",
            },
            messages: {
                firstname: "Please enter your firstname",
                lastname: "Please enter your lastname",
                username: {
                    required: "Please enter a username",
                    minlength:
                        "Your username must consist of at least 2 characters",
                },
                password: {
                    required: "Please provide a password",
                    minlength:
                        "Your password must be at least 5 characters long",
                },
                confirm_password: {
                    required: "Please provide a password",
                    minlength:
                        "Your password must be at least 5 characters long",
                    equalTo: "Please enter the same password as above",
                },
                email: "Please enter a valid email address",
                agree: "Please accept our policy",
                topic: "Please select at least 2 topics",
            },
            errorPlacement: function (label, element) {
                label.addClass("mt-2 text-danger");
                label.insertAfter(element);
            },
            highlight: function (element, errorClass) {
                $(element).parent().addClass("has-danger");
                $(element).addClass("form-control-danger");
            },
        });
        // propose username by combining first- and lastname
        $("#username").focus(function () {
            var firstname = $("#firstname").val();
            var lastname = $("#lastname").val();
            if (firstname && lastname && !this.value) {
                this.value = firstname + "." + lastname;
            }
        });
        //code to hide topic selection, disable for demo
        var newsletter = $("#newsletter");
        // newsletter topics are optional, hide at first
        var inital = newsletter.is(":checked");
        var topics = $("#newsletter_topics")[
            inital ? "removeClass" : "addClass"
        ]("gray");
        var topicInputs = topics.find("input").attr("disabled", !inital);
        // show when newsletter is checked
        newsletter.on("click", function () {
            topics[this.checked ? "removeClass" : "addClass"]("gray");
            topicInputs.attr("disabled", !this.checked);
        });
        $("#projectForm").validate({
            rules: {
                projectname: {
                    required: true,
                    minlength: 10,
                },
                description: {
                    required: true,
                    minlength: 30,
                },
                customFrom: {
                    required: true,
                    min: 10,
                },
                customTo: {
                    required: true,
                    min: 11,
                },
                skills: {
                    required: true,
                },
            },
            messages: {
                projectname: {
                    required: "Vui lòng nhập tên dự án",
                    minlength: "Tên dự án cần có ít nhất 10 ký tự",
                },
                description: {
                    required: "Vui lòng miêu tả dự án",
                    minlength: "Nội dung miêu tả cần ít nhất 30 ký tự",
                },
                customFrom: {
                    required: "Trường này không được bỏ trống",
                    min: "Giá trị này này không được nhỏ hơn 10",
                },
                customTo: {
                    required: "Trường này không được bỏ trống",
                    min: "Giá trị này này phải lớn hơn 10",
                },
                skills: {
                    required: "Vui lòng chọn ít nhất một kỹ năng",
                },
            },
            errorPlacement: function (label, element) {
                if (element.attr("id") == "skills") {
                    label.html("Vui lòng chọn ít nhất một kỹ năng");
                    label.addClass("mt-2 text-danger");
                    label.insertAfter(".select2-container");
                } else {
                    label.addClass("mt-2 text-danger");
                    label.insertAfter(element);
                }
            },
            highlight: function (element, errorClass) {
                $(element).parent().addClass("has-danger");
                $(element).addClass("form-control-danger");
            },
        });
        $("#forgotPassForm").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
            },
            messages: {
                email: {
                    required: "Vui lòng nhập email để đặt lại mật khẩu",
                    email: "Email không hợp lệ",
                },
            },
            errorPlacement: function (label, element) {
                label.addClass("mt-2 text-danger");
                label.insertAfter(element);
            },
            highlight: function (element, errorClass) {
                $(element).parent().addClass("has-danger");
                $(element).addClass("form-control-danger");
            },
        });
    });
})(jQuery);
