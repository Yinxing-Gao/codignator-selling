jQuery(document).ready(function () {
    if ($("div").is("#progressbar")) {
        $(function () {
            $("#progressbar").progressbar({
                value: $('#production_process').attr('data-progress')
                // value: parseInt($("#production_process").attr('data-progress'))
            });
        });
    }


// $(function () {
//     var progressbar = $("#progressbar"),
//         progressLabel = $(".progress-label");
//     var value = $("#progressbar").attr('data-value');
//     progressbar.progressbar({
//         value: value,
//         // change: function () {
//         //     progressLabel.text(progressbar.progressbar("value") + "%");
//         // },
//     });

// function progress(position) {
//     var val = progressbar.progressbar("value") || 0;
//     progressbar.progressbar("value", val + 2);
//
//     if (val <= position) {
//         setTimeout(progress(position), 200);
//     }
// }
//
// progress(45);
// });


//operation_add_income -  додати прихід
    jQuery(document).on('change', '.machine_block', function (event) {
        var select = $(this);
        var block_id = select.val();
        event.preventDefault();

        var form = $(this).closest('form');
        $.ajax({
            type: "POST",
            url: '/production/change_block_ajax/' + select.attr('data-name_id'),
            data: {
                block_id: block_id
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    // var success = '<h3 class="success">Заявку успішно створено</h3><span class="normal">Номер вашої заявки<br/></span><span class="number">' + data.id + '</span> <span class="normal">Зараз вас перенаправить на список ваших заявок</span>';
                    // open_popup(success);
                    // setTimeout(function () {
                    location.reload();
                    // }, 3000);
                } else {
                    alert(data.message);
                }
            }
        });

    });

    $('html').on('click', '.edit_specification_name', function (e) {
        e.preventDefault();
        var btn = $(this);

        $('.amount_td_input').hide();
        $('.amount_td_span').show();
        $('.done_editing_specification_name').hide();
        $('.edit_specification_name').show();

        var td_amount = btn.parents('tr').find('.amount_td');
        var input_span = '<input class="amount_td_input" style="width:40px" type="number" value="' + td_amount.find('span').html() + '" /><span class="amount_td_span" style="display:none">' + td_amount.find('span').html() + '</span>';
        td_amount.html(input_span);
        btn.hide();
        btn.next('.done_editing_specification_name').show();
    });

    $('html').on('click', '.done_editing_specification_name', function (e) {
        e.preventDefault();
        var btn = $(this);

        var td_amount = btn.parents('tr').find('.amount_td input').val();
        $.ajax({
            type: "POST",
            url: '/production/change_amount_ajax/' + $(this).parents('tr').attr('data-name_id'),
            data: {
                amount: td_amount,
            },
            success: function (data) {
                data = JSON.parse(data);
                btn.hide();
                btn.prev('.edit_specification_name').show();
                btn.parents('tr').find('.amount_td').find('span').html(td_amount);
                $('.amount_td_input').hide();
                $('.amount_td_span').show();
            }
        });
    });

    $('html').on('click', '.delete_specification_name', function (e) {
        e.preventDefault();
        var btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цю деталь зі специфікації? ")) {
            $.ajax({
                type: "POST",
                url: '/production/delete_item_from_specification_ajax/' + $(this).parents('tr').attr('data-name_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $('html').on('click', '.add_to_specification', function (e) {
        e.preventDefault();
        var btn = $(this);
        var product_id = $('[name=product_id]').val();
        var id_arr = [];
        $('.storage_to_spec').each(function (index) {
            if ($(this).is(':checked')) {
                id_arr.push($(this).parents('tr').attr('data-item_id'));
            }
        });

        $.ajax({
            type: "POST",
            url: '/production/to_spec_ajax',
            data: {
                id_arr: JSON.stringify(id_arr),
                product_id: product_id
            },
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '.copy_specification', function (e) {
        e.preventDefault();
        var from_id = $('#specifications').val();
        var errors = [];
        if (from_id === '') {
            errors.push('Виберіть, будь ласка, специфікацію зі списку')
        }
        console.log(parseInt(from_id));
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/production/copy_spec_ajax',
                data: {
                    from_id: from_id,
                    to_id: $('[name=product_id]').val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        } else {
            var error_html = '';
            $.each(errors, function (index, value) {
                error_html += '<span class="error">' + value + '</span>';
            });
            open_popup(error_html)

        }
    });


    $('html').on('click', '.generate_app_from_spec', function (e) {
        e.preventDefault();
        var btn = $(this);
        $.ajax({
            type: "POST",
            url: '/production/generate_app_from_spec_ajax/' + $('[name=project_id]').val(),
            data:
                {
                    missing: btn.attr('data-missing')
                },
            success: function (data) {
                data = JSON.parse(data);
                window.location = '/application';
            }
        });
    });

    $('html').on('click', '.add_all_from_spec_to_project', function (e) {
        e.preventDefault();
        let ids = [];
        $('.specification_item_left').each(function (index) {
            ids.push({
                id: $(this).attr('data-item_id'),
                storage_item_id: $(this).attr('data-storage_item_id'),
            });
        });
        console.log(ids);
        $.ajax({
            type: "POST",
            url: '/production/move_to_project_ajax/' + $('[name=project_id]').val(),
            data: {
                ids: ids,
            },
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '.add_block_from_spec_to_project', function (e) {
        e.preventDefault();
        let block_id = $(this).attr('data-block_id');
        let ids = [];
        console.log('.block_item_' + block_id + '_left');
        console.log($('.block_item_' + block_id + '_left'));
        $('.block_item_' + block_id + '_left').each(function (index) {
            ids.push({
                id: $(this).attr('data-item_id'),
                storage_item_id: $(this).attr('data-storage_item_id'),
            });
        });
        console.log(ids);
        $.ajax({
            type: "POST",
            url: '/production/move_to_project_ajax/' + $('[name=project_id]').val(),
            data: {
                ids: ids,
            },
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '.add_item_from_spec_to_project', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '/production/move_to_project_ajax/' + $('[name=project_id]').val(),
            data: {
                ids: {
                    0: {
                        id: $(this).parents('tr').attr('data-item_id'),
                        storage_item_id: $(this).parents('tr').attr('data-storage_item_id'),
                    }
                },
            },
            success: function (data) {
                data = JSON.parse(data);
                // location.reload();
            }
        });
    });

    $('html').on('change', '.storage_purchase #storage', function (e) {
        let storage_id = $(this).val();
        $.ajax({
            type: "POST",
            url: '/storage/get_names_ajax/' + storage_id,
            success: function (data) {
                data = JSON.parse(data);
                let names = '';
                data.forEach(function (name) {
                    names += '<span class="storage_names_for_purchase" ' +
                        'data_id="' + name.id + '" ' +
                        'data_price="' + name.buy_price + '" ' +
                        'data_amount = "' + name.amount + '"' +
                        'data_unit = "' + name.unit + '">' +
                        name.name + '</span>';
                });
                if (names.length > 0) {
                    $('.storage_names_for_purchase_block').html(names);
                } else {
                    $('.storage_names_for_purchase_block').html('<p>На цьому складі поки немає найменувань. Додайте найменування, будь ласка, <a href="/storage/add_names/' + storage_id + '">тут</a>');
                }
            }
        });
    });

    if ($(".storage_names").length > 0) {
        $(".storage_names").each(function(){
            $(this).select2({
                placeholder: 'Виберіть товар',
                // minimumInputLength: 2,
                customClass: "form-control",
                language: {
                    inputTooShort: function () {
                        return 'Введіть мінімум 2 символи';
                    },
                    // noResults: function () {
                    //     return '<button class="form-control" id="add_new_contract">Додати договір</a>';
                    // },
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                ajax: {
                    url: '/storage/get_names_ajax/' + $(this).attr('id'),
                    dataType: "json",
                    type: 'POST',
                    processResults: function (response) {
                        return {
                            results: $.map(response, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    }
                }
            });
        });
    }

    let specification_id = $('#specification_id').val()
    let files; // переменная. будет содержать данные файлов
    $('#specification input[type=file]').on('change', function (event) {
        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

        files = this.files;
        upload_files(files, '/production/upload_spec_ajax/' + specification_id, 'spec', function (response) {
            if (response.status === 'ok') {
                // $('#users_table').append('<tr class="success"><td colspan="4">' + response.message + '</td></tr>')
                // $('.uploaded_file').html('');
                location.reload();
            }
        })
    });

    jQuery(document).on('change', 'select.specification_select', function (event) {
        var select = $(this);
        var subspecification_id = select.val();
        var specification_id = $('#specification_id').val();
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/production/add_specification_item_ajax/' + specification_id,
            data: {
                subspecification_id: subspecification_id,
                type: "specification",
                amount: "1"
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === 'ok') {
                    let new_record = response.new_record;
                    $('.specifications_table_div tbody tr:first').before('<tr data-name_id="' + new_record.id + '"><td>' + new_record.subspecification_id + '</td><td>' + new_record.name + '</td>' +
                        '<td><input type="number" value="' + new_record.amount + '" class="form-control amount_ajax"/></td><td>шт</td><td><img class="delete_specification_name" src="/icons/fineko/delete.svg"/></td></tr>');

                    select.find('option:selected').remove();
                } else {
                    alert(response.message);
                }
            }
        });
    });

    jQuery(document).on('change', 'select.storage_names_select', function (event) {
        var select = $(this);
        var storage_name_id = select.val();
        var specification_id = $('#specification_id').val();
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/production/add_specification_item_ajax/' + specification_id,
            data: {
                storage_name_id: storage_name_id,
                type: "storage_name",
                amount: "1"
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === 'ok') {
                    let new_record = response.new_record;
                    $('.storage_names_table_div tbody tr:first').before('<tr data-name_id="' + new_record.id + '"><td>' + new_record.storage_name_id + '</td><td>' + new_record.name + '</td>' +
                        '<td>' + '' + '</td><td><input type="number" value="' + new_record.amount + '" class="form-control amount_ajax"/></td><td>шт</td>' +
                        '<td>' + new_record.storage_name + '</td><td><img class="delete_specification_name" src="/icons/fineko/delete.svg"/></td></tr>');

                    select.find('option:selected').remove();
                } else {
                    alert(response.message);
                }
            }
        });
    });

    jQuery(document).on('blur', '.amount_ajax', function (event) {
        let amount_field = $(this);
        let amount = $(this).val();
        let item_id = $(this).parents('tr').attr('data-name_id');
        $.ajax({
            type: "POST",
            url: '/production/change_amount_ajax/' + item_id,
            data: {
                amount: amount,
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === 'ok') {
                    amount_field.css("border", "2px solid lightgreen");
                    setTimeout(function(){
                        amount_field.css("border", "");
                    }, 3000);
                }
                else{
                    amount_field.css("border", "2px solid red");
                    setTimeout(function(){
                        amount_field.css("border", "");
                    }, 3000);
                }
            }
        });
    });

    jQuery(document).on('blur', '#specification_name_field', function (event) {
        let name_field = $(this);
        let name = $(this).val();
        let specification_id = $('#specification_id').val();
        $.ajax({
            type: "POST",
            url: '/production/change_specification_name_ajax/' + specification_id,
            data: {
                name: name,
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === 'ok') {
                    $("#specification_name_title").html(name);
                    name_field.css("border", "2px solid lightgreen");
                    setTimeout(function(){
                        name_field.css("border", "");
                    }, 3000);
                }
                else{
                    name_field.css("border", "2px solid red");
                    setTimeout(function(){
                        name_field.css("border", "");
                    }, 3000);
                }
            }
        });
    });

    $('html').on('click', '#add_subspecification_button', function (e) {
        e.preventDefault();
        var button = $(this);
        var specification_id = $('#specification_id').val();
        $.ajax({
            type: "POST",
            url: '/production/add_new_subspecification_ajax/' + specification_id,
            success: function (response) {
                response = JSON.parse(response);
                if (response.status === 'ok') {
                    window.location.href = "/production/specification/" + response.id;
                } else {
                    alert(response.message);
                }
            }
        });
    });
});