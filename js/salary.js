jQuery(document).ready(function () {
    $('html').on('change', '#container_working_hours #month_change', function (e) {
        let user_id = $('[name=selected_user_id]').val();
        if (user_id.length > 0) {
            window.location = '/salary/hours/' + $(this).val() + '/' + user_id;
        } else {
            window.location = '/salary/hours/' + $(this).val();
        }
    });

    console.log($('#container_salary_pay #month_change'));
    $('html').on('change', '#container_salary_pay #month_change', function (e) {
        console.log('ddd');
        let user_id = $('[name=selected_user_id]').val();
        if (user_id.length > 0) {
            window.location = '/salary/pay/' + $(this).val() + '/' + user_id;
        } else {
            window.location = '/salary/pay/' + $(this).val();
        }
    });


    $('html').on('change', '#container_working_hours .hours_input', function (e) {
        let input = $(this);
        $.ajax({
            type: "POST",
            url: '/salary/change_hours_ajax',
            data: {
                user_id: input.attr('data-user_id'),
                date: input.attr('data-date'),
                hours: input.val()
            },
            success: function (data) {
                data = JSON.parse(data);
            }
        });
    });

});