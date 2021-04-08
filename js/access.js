$(document).ready(function () {
    $('html').on('change', '.access_checkbox', function (e) {
        let checkbox = $(this);
        let department_id = $('#access_department_id').val();
        let access_id = $(this).parents('tr').attr('data-access_id');

        $.ajax({
            type: "POST",
            url: '/access/change_department_access_ajax/' + department_id,
            data: {
                access_id: access_id,
                value: checkbox.prop("checked")
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    notification('Зміни успішно збережено');
                } else {
                    alert(data.message);
                }
            }
        });
    });

    $('html').on('click', '.access_details', function (e) {
        let access_id = $(this).parents('tr').attr('data-access_id');

        $.ajax({
            type: "POST",
            url: '/access/details_ajax/' + access_id,
            data: {
                department_id: $('#access_department_id').val()
            },
            success: function (data) {
                data = JSON.parse(data);
                    $('.access_details_block').html(data);
            }
        });
    });
});
