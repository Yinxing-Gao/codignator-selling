jQuery(document).ready(function () {
    $('html').on('change', '#accrual_list #month_change', function (e) {
        window.location = '/accruals/index/' + $(this).val();
    });
});