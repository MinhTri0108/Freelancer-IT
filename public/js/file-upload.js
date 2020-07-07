(function ($) {
  'use strict';
  $(function () {
    $('#file-upload-browse').on('click', function () {
      var file = $(this).parent().parent().parent().find('#avatar');
      file.trigger('click');
    });
    $('#avatar').on('change', function () {
      $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
      var input = this;
      if (input.files && input.files[0]) {
        var file = input.files[0];
        var fr = new FileReader();
        fr.onload = function (e) {
          $('#preview-img').attr('src', e.target.result);
        }
        fr.readAsDataURL(file);
        if (input.files[0].size > 1048576) {
          $('#avatarForm').append('<div id="alert-ava" class="alert alert-danger">Size ảnh không được vượt quá 1MB</div>')
        } else {
          $('#alert-ava').remove();
        }
      }
    });
  });
})(jQuery);