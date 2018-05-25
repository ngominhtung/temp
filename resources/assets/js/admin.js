/* Admin helpers */
$(document).ready(function() {
    $('.import_btn').on('click', function() {
        $('.register_row_4 > div:not(.error-csv)').remove();
        $('.error-csv').hide();
        $('#import_form input').click();
    });

    $('#import_form input').on('change', function() {
        var arr = this.value.split('.'),
            ext = arr[arr.length - 1];
        if (ext.indexOf('csv') === -1) {
            this.value = '';
            $('.error-csv').show();
        }
        else
            $('#import_form').submit();
    });
});