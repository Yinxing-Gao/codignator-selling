$(document).ready(function () {
    $(document).on('click', '.btn_add_contractor', function (event) {
        event.preventDefault();
        let form = $(this).closest('form');
        let action = $(this).attr('data-action');

        let url = '';
        switch (action) {
            case 'add':
                url = '/contractor/add_ajax';
                break;
            case 'update':
                url = '/contractor/update_ajax';
                break;
            case 'unite':
                url = '/contractor/unite_ajax';
        }

        $.ajax({
            type: "POST",
            method: "POST",
            url: url,
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });

    });


    $('html').on('click', '.delete_contractor', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цього контрагента? ")) {
            $.ajax({
                type: "POST",
                url: '/contractor/delete_ajax/' + $(this).parents('tr').attr('data-contractor_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $('html').on('click', '.check', function (e) {
        let ids = [];
        let form = $('.contractor_form');
        form.find('[name=id]').remove();
        $('.table.contractors_list tbody tr .check').each(function (index) {
            if ($(this).prop("checked") === true) {
                ids.push($(this).parents('tr').attr('data-contractor_id'));
            }
        });

        if (ids.length === 0) {
            $('.action_select').html('<option value="add">Додати</option>');
            $('.btn_add_contractor').attr('data-action', 'add');

            form.find("[name=name]").val('');
            form.find("[name=contractor_type]").val('');
            form.find("[name=address]").val('');
            form.find("[name=phone]").val('');
            form.find("[name=options]").val('');
        } else if (ids.length === 1) {
            $('.action_select').html('<option value="edit">Редагувати</option>');
            $('.btn_add_contractor').attr('data-action', 'update');
            form.prepend('<input type="hidden" name="id"  value="' + ids[0] + '">');

            $.ajax({
                type: "POST",
                method: "POST",
                url: '/contractor/get_one_ajax/' + ids[0],
                success: function (data) {
                    data = JSON.parse(data);
                    form.find("[name=name]").val(data.name);
                    form.find("[name=contractor_type]").val(data.contractor_type);
                    form.find("[name=address]").val(data.address);
                    form.find("[name=phone]").val(data.phone);
                    form.find("[name=options]").val(data.options);
                }
            });
        } else if (ids.length > 1) {
            $('.action_select').html('<option value="unite">Об\'єднати</option>');
            $('.btn_add_contractor').attr('data-action', 'unite');
            form.prepend('<input type="hidden" name="id"  value="' + ids.join() + '">');

            $.ajax({
                type: "POST",
                method: "POST",
                url: '/contractor/get_ajax',
                data: {
                    ids: ids
                },
                success: function (data) {
                    data = JSON.parse(data);
                    console.log(data);
                    let united = {
                        name: [],
                        type: [],
                        adress: '',
                        phone: '',
                        options: ''
                    };
                    $.each(data, function (index, contractor) {
                        if (contractor.name.length > 0) {
                            united.name.push(contractor.name);
                        }
                        if (contractor.contractor_type !== undefined && contractor.contractor_type.length > 0) {
                            united.type.push(contractor.contractor_type);
                        }
                        if (contractor.adress !== undefined && contractor.adress.length > 0) {
                            united.address += contractor.address;
                        }
                        if (contractor.phone !== undefined && contractor.phone.length > 0) {
                            united.phone += ', ' + contractor.phone;
                        }
                        if (contractor.options !== undefined && contractor.options.length > 0) {
                            united.options += ', ' + contractor.options;
                        }
                    });
                    form.find("[name=name]").val(united.name[0]);
                    form.find("[name=contractor_type]").val(united.type[0]);
                    form.find("[name=address]").val(united.address);
                    form.find("[name=phone]").val(united.phone);
                    form.find("[name=options]").val(united.options);
                }
            });
        }
    });

    $(document).on('click', '.create_telegram_code', function (e) {
        e.preventDefault();
        console.log('test');
        let btn = $(this);
        let contractor_id = btn.parents('tr').attr('data-contractor_id');
        $.ajax({
            type: "POST",
            url: '/telegram/generate_contractor_code_ajax/' + contractor_id,
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    open_small_popup(
                        data.message
                        , 400, 300);
                } else {
                    alert(data.message);
                }
            }
        });
    });
});