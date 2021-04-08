$(document).ready(function () {
    $(document).on('click', '.open_department_sidenav', function (event) {
        event.preventDefault();
        openNav('department_sidenav', 300);
    });

    $('#department_list [name=modules], #modules').select2({
        placeholder: 'Модулі',
        customClass: "form-control",
        escapeMarkup: function (markup) {
            return markup;
        },
        ajax: {
            url: '/department/get_modules_ajax',
            dataType: "json",
            type: 'POST',
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            options: item.options,
                            id: item.id,
                        }
                    })
                };
            },
        }
    });

    $(document).on('click', '.btn_add_department', function (event) {
        event.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/department/add_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                // location.reload();
            }
        });
    });

    $('html').on('click', '.delete_department', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей департамент і його дочірніх? ")) {
            $.ajax({
                type: "POST",
                url: '/department/delete_ajax/' + btn.parents('tr').attr('data-department_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $('html').on('change', '#department_list .is_shown', function (e) {
        let checkbox = $(this);
        if ($(this).prop("checked") === true) {
        }
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/department/change_visibility_ajax/' + checkbox.parents('tr').attr('data-department_id'),
            data: {
                is_shown: checkbox.prop("checked") === true ? 1 : 0
            },
            success: function (data) {
                // data = JSON.parse(data);
                // location.reload();
            }
        });
    });
});
