jQuery(document).ready(function () {
    $('html').on('click', '.delete_operation', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цю операцію? ")) {
            $.ajax({
                type: "POST",
                url: '/operation/delete_operation_ajax/' + $(this).parents('tr').attr('data-operation_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $(document).on('click', '.edit_operation', function (event) {
        event.preventDefault();
        let btn = $(this);
        openNav('operation_sidenav', 300);
        $.ajax({
            type: "POST",
            url: '/operation/get_ajax/' + btn.parents('tr').attr('data-operation_id'),
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    fill_operation_sidenav(data.result);
                } else {
                    alert(data.message);
                }
            }
        });
    });

    $('html').on('click', '.delete_template', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей шаблон? Операції, які були створені раніше з цього шаблону залишаться ")) {
            $.ajax({
                type: "POST",
                url: '/operation/delete_operation_ajax/' + $(this).parents('tr').attr('data-template_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $(document).on('click', '.edit_template', function (event) {
        event.preventDefault();
        let btn = $(this);
        openNav('operation_sidenav', 300);
        $.ajax({
            type: "POST",
            url: '/operation/get_ajax/' + btn.parents('tr').attr('data-template_id'),
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    fill_operation_sidenav(data.result);
                } else {
                    alert(data.message);
                }
            }
        });
    });

    $('html').on('click', '#storage_purchase_show', function (e) {
        if ($(this).prop("checked") === true) {
            $('.storage_purchase').show();
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".storage_purchase").offset().top
            }, 600);
        } else {
            $('.storage_purchase').hide();
        }
    });

    $('html').on('click', '.storage_names_for_purchase', function (e) {
        let name = $(this).text();
        let id = $(this).attr('data_id');
        let price = $(this).attr('data_price');
        let amount = $(this).attr('data_amount');
        let unit = $(this).attr('data_unit');

        let tr = '<tr data_name_id="' + id + '">' +
            '<td class="name">' + name + '</td>' +
            '<td><input type="number" name="price" value="' + price + '" /> </td>' +
            '<td><input type="number" name="amount" value="' + amount + '" /> </td>' +
            '<td class="unit">' + unit + '</td>' +
            '<td class="total">' + parseFloat(price * amount).toFixed(2) + ' грн</td>' +
            '<td><img class="delete_purchase_from_extence" src="../../img/trash-icon.jpg"/></td>' +
            '</tr>'

        $('#storage_purchase_table tbody').append(tr);

        $('.storage_purchase_table_block').animate({
            scrollTop: $('.storage_purchase_table_block').height()
        }, 200);

        $(this).hide();
    });

    $('html').on('change', '#storage_purchase_table [name=price], #storage_purchase_table [name=amount]', function (e) {
        let price = $(this).parents('tr').find('[name=price]').val();
        let amount = $(this).parents('tr').find('[name=amount]').val();
        $(this).parents('tr').find('.total').html(parseFloat(price * amount).toFixed(2) + ' грн');
    });

    $('html').on('change', '#container_operation_add_expenses [name=amount], #container_operation_add_expenses [name=currency]', function (e) {
        let price = parseFloat($('#container_operation_add_expenses [name=amount]').val()) || 0;
        let currency = $('#container_operation_add_expenses [name=currency]').val();
        let currency_rate = JSON.parse($('#currency').attr('data-currency_rate'));
        let total_uah;
        if (currency === 'UAH') {
            total_uah = price;
        } else {
            total_uah = price * currency_rate[currency]['buy']
        }
        $('#container_operation_add_expenses [name=total_uah]').val(total_uah);
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

    $('html').on('click', '.delete_purchase_from_extence', function (e) {
        let name_id = $(this).parents('tr').attr('data_name_id');
        $('.storage_names_for_purchase').each(function (index) {
            if ($(this).attr('data_id') == name_id) {
                $(this).show();
            }
        });
        $(this).parents('tr').remove();
    });

    //operation_add_expenses -  додати витрату
    // jQuery(document).on('click', '.btn_add_expense', function (event) {
    //     event.preventDefault();
    //
    //     let errors = [];
    //     let items = [];
    //     let items_comment = '';
    //
    //     if ($('#wallet_1_id').val() === '') {
    //         errors.push('Виберіть, будь ласка, касу. Це потрібно, щоб формувати баланси по кожній касі')
    //     }
    //     if ($('#amount').val() <= 0) {
    //         errors.push('Введіть, будь ласка, корректну суму.')
    //     }
    //
    //     if (($('[name=contractor_type]:checked').val() === 'new' && $('#contractor_new').val() == '') ||
    //         ($('[name=contractor_type]:checked').val() === 'existing' && $('#contractor_id').val() == '')) {
    //         errors.push('Виберіть, будь ласка, існуючого контрагента або добавте нового');
    //     }
    //
    //     // if ($('#app').val() === '') {
    //     //     errors.push('Виберіть, будь ласка, по якій заявці проводиться дана операція. Всі операції повинні проходити тільки по заявках');
    //     // }
    //
    //     if ($('#comment').val().length <= 10) {
    //         errors.push('Введіть, будь ласка, корректний коментар. Він має бути не коротший 10 символів')
    //     }
    //
    //     if ($('#contractor_id').val() === '') {
    //         errors.push('Введіть або виберіть, будь ласка, контрагента');
    //     }
    //
    //     if ($('#is_planned').prop("checked") === true && $('#plan_date').val() === '') {
    //         errors.push('Введіть, будь ласка, плановану дату операції, або дату операції, якщо це уже здійснена операція');
    //     }
    //
    //     if ($('#storage_purchase_show').prop("checked") === true) { // якщо це закупка на склад
    //         if ($('.storage_purchase #storage').val() === '') {
    //             errors.push('Виберіть, будь ласка, склад, на який робиться закупка');
    //         }
    //
    //         let operation_amount = parseFloat($('#container_operation_add_expenses [name=total_uah]').val());
    //         let total = 0;
    //
    //         $('#storage_purchase_table tbody tr').each(function (index) {
    //             let id = $(this).attr('data_name_id');
    //             let price = $(this).find('[name=price]').val();
    //             let amount = $(this).find('[name=amount]').val();
    //             let name = $(this).find('.name').text();
    //             let unit = $(this).find('.unit').text();
    //             let total = $(this).find('.total').text();
    //             let item = {
    //                 id: id,
    //                 price: price,
    //                 amount: amount
    //             };
    //             items.push(item);
    //             items_comment += name + ' (' + price + ' грн) - ' + amount + ' ' + unit + ' - ' + total + ' грн' + " \r\n";
    //             total = parseFloat(parseFloat(total) + parseFloat(price * amount)).toFixed(2);
    //         });
    //         // console.log(operation_amount);
    //         // console.log(total);
    //         if (operation_amount < total) {
    //             errors.push('Сума по операції в гривні - ' + operation_amount + ' грн<br/>Загальна сума закуплених товарів - ' + total + ' грн<br/> Сума по операції не може бути менше суми закуплених товарів')
    //         }
    //     }
    //
    //     let data = {
    //         user_id: $('[name=user_id]').val(),
    //         wallet_1_id: $('[name=wallet_1_id]').val(),
    //         amount: $('[name=amount]').val(),
    //         currency: $('[name=currency]').val(),
    //         contractor_id: $('[name=contractor_id]').val(),
    //         contractor_type: $('[name=contractor_type]').val(),
    //         contractor_new: $('[name=contractor_new]').val(),
    //         project_id: $('[name=project_id]').val(),
    //         app_id: $('[name=app_id]').val(),
    //         comment: $('[name=comment]').val(),
    //         storage_purchase_show: ($('[name=storage_purchase_show]').prop("checked") == true) ? true : false,
    //         storage_id: $('[name=storage_id]').val(),
    //         items: items,
    //         items_comment: items_comment
    //     };
    //     if ($('#is_planned').prop("checked") === true) {
    //         data.plan_date = $('[name=plan_date]').val();
    //         data.is_planned = true;
    //     } else {
    //         data.date = $('[name=date]').val();
    //     }
    //     if (errors.length === 0) {
    //         $.ajax({
    //             type: "POST",
    //             url: '/operation/add_expense_ajax',
    //             // data: form.serialize() + '&' + $.param(data),
    //             data: data,
    //             success: function (data) {
    //                 data = JSON.parse(data);
    //                 if (data.status === 'ok') {
    //                     if ($('#is_planned').prop("checked") === true) {
    //                         window.location = '/operation/plan';
    //                     } else {
    //                         window.location = '/operation';
    //                     }
    //                 } else {
    //                     alert(data.message);
    //                 }
    //             }
    //         });
    //     } else {
    //         let error_html = '';
    //         $.each(errors, function (index, value) {
    //             error_html += '<span class="error">' + value + '</span>';
    //         });
    //         open_popup(error_html)
    //
    //     }
    // });

    //operation_add_expenses + //operation_add_income
    $('html').on('click', '[name=contractor_type]', function () {
        let val = $('[name=contractor_type]:checked').val();
        if (val === 'existing') {
            $('#contractor_new').attr('disabled', 'disabled');
            $('#contractor_id').removeAttr('disabled');
        } else {
            if (val === 'new') {
                $('#contractor_id').attr('disabled', 'disabled');
                $('#contractor_new').removeAttr('disabled');
            }
        }
    });


    //operation_add_income -  додати прихід
    // jQuery(document).on('click', '.btn_add_income', function (event) {
    //     event.preventDefault();
    //     let account_id = $('#account_id').val();
    //     let errors = [];
    //     if ($('#wallet_2_id').val() === '') {
    //         errors.push('Виберіть, будь ласка, касу. Це потрібно, щоб формувати баланси по кожній касі')
    //     }
    //     if ($('#amount').val() <= 0) {
    //         errors.push('Введіть, будь ласка, корректну суму.')
    //     }
    //     console.log($('[name=contractor_type]:checked').val());
    //     if (($('[name=contractor_type]:checked').val() === 'new' && $('#contractor_new').val() == '') ||
    //         ($('[name=contractor_type]:checked').val() === 'existing' && $('#contractor_id').val() == '')) {
    //         errors.push('Виберіть, будь ласка, існуючого контрагента або добавте нового');
    //     }
    //
    //     if ($('#comment').val().length <= 10) {
    //         errors.push('Введіть, будь ласка, корректний коментар. Він має бути не коротший 10 символів')
    //     }
    //
    //     let form = $(this).closest('form');
    //     if (errors.length === 0) {
    //         $.ajax({
    //             type: "POST",
    //             url: '/operation/add_income_ajax',
    //             data: form.serialize(),
    //             success: function (data) {
    //                 data = JSON.parse(data);
    //                 if (data.status === 'ok') {
    //                     // let success = '<h3 class="success">Заявку успішно створено</h3><span class="normal">Номер вашої заявки<br/></span><span class="number">' + data.id + '</span> <span class="normal">Зараз вас перенаправить на список ваших заявок</span>';
    //                     // open_popup(success);
    //                     // setTimeout(function () {
    //                     window.location = '/operation';
    //                     // }, 3000);
    //                 } else {
    //                     alert(data.message);
    //                 }
    //             }
    //         });
    //     } else {
    //         let error_html = '';
    //         $.each(errors, function (index, value) {
    //             error_html += '<span class="error">' + value + '</span>';
    //         });
    //         open_popup(error_html)
    //
    //     }
    // });

    //operation_add_transfer
    $('html').on('change', '#container_operation_add_transfer #wallet_1_id', function () {
        let user_id = $('#wallet_1_id option:selected').attr('data_user_id');
        $('[name=user_id]').val(user_id);
    });

    //operation_add_expense
    $('html').on('change', '#container_operation_add_expenses #wallet_1_id', function () {
        let user_id = $('#wallet_1_id option:selected').attr('data_user_id');
        $('[name=user_id]').val(user_id);
    });

    //operation_add_income
    $('html').on('change', '#container_operation_add_income #wallet_2_id', function () {
        let user_id = $('#wallet_2_id option:selected').attr('data_user_id');
        $('[name=user_id]').val(user_id);
    });

    // //operation_add_transfer -  додати переміщення
    // jQuery(document).on('click', '.btn_add_transfer', function (event) {
    //     event.preventDefault();
    //     let errors = [];
    //     if ($('#wallet_1_id').val() === '') {
    //         errors.push('Виберіть, будь ласка, касу. Це потрібно, щоб формувати баланси по кожній касі')
    //     }
    //     if ($('#amount').val() <= 0) {
    //         errors.push('Введіть, будь ласка, корректну суму.')
    //     }
    //
    //     if ($('#comment').val().length <= 10) {
    //         errors.push('Введіть, будь ласка, корректний коментар. Він має бути не коротший 10 символів')
    //     }
    //
    //     let form = $(this).closest('form');
    //     if (errors.length === 0) {
    //         $.ajax({
    //             type: "POST",
    //             url: '/operation/add_transfer_ajax',
    //             data: form.serialize(),
    //             success: function (data) {
    //                 data = JSON.parse(data);
    //                 if (data.status === 'ok') {
    //                     window.location = '/operation';
    //                 } else {
    //                     alert(data.message);
    //                 }
    //             }
    //         });
    //     } else {
    //         let error_html = '';
    //         $.each(errors, function (index, value) {
    //             error_html += '<span class="error">' + value + '</span>';
    //         });
    //         open_popup(error_html)
    //
    //     }
    // });

    //operation_add_transfer
    $('html').on('change', '#user2', function () {
        let select = $(this);
        console.log(select.val());
        $('#wallet_2_id option').each(function (index) {
            $(this).show();
            if ($(this).attr('data_user_id') !== select.val()) {
                $(this).hide();
            } else {
                if ($(this).attr('data_currency') === $('#currency').val()) {
                    $('#wallet_2_id').val($(this).val());
                }
            }
        });
    });

    $(document).on('change', '.operation_application_id', function (event) {
        let select = $(this);
        let app_id = select.val();
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/operation/change_app_in_operation_ajax/' + select.parents('tr').attr('data-operation_id'),
            data: {
                app_id: app_id
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    // let success = '<h3 class="success">Заявку успішно створено</h3><span class="normal">Номер вашої заявки<br/></span><span class="number">' + data.id + '</span> <span class="normal">Зараз вас перенаправить на список ваших заявок</span>';
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

    $(document).on('change', 'table .article_id', function (event) {
        let select = $(this);
        let expense_id = select.val();
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/operation/change_expense_in_operation_ajax/' + select.parents('tr').attr('data-operation_id'),
            data: {
                expense_id: expense_id
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    notification('Стаття успішно змінена');
                } else {
                    alert(data.message);
                }
            }
        });

    });

    $(document).on('change', '.operation_department_id', function (event) {
        let select = $(this);
        let department_id = select.val();
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/operation/change_department_in_operation_ajax/' + select.parents('tr').attr('data-operation_id'),
            data: {
                department_id: department_id
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    // location.reload();
                } else {
                    alert(data.message);
                }
            }
        });

    });

    let data = [{id: 0, text: 'enhancement'}, {id: 1, text: 'bug'}, {id: 2, text: 'duplicate'}, {
        id: 3,
        text: 'invalid'
    }, {id: 4, text: 'wontfix'}];


    // $("#contractor_id").select2({
    //     matcher: matchContractor,
    //    data:data
    // });

    if ($("#contractor_id").length > 0) {
        $("#contractor_id").select2({
            placeholder: 'Контрагент',
            // minimumInputLength: 2,
            customClass: "form-control",
            language: {
                inputTooShort: function () {
                    return 'Введіть мінімум 2 символи';
                },
                noResults: function () {
                    return '<button class="form-control" id="add_new_contractor">Додати контрагента</a>';
                },
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/contractor/search_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                options: item.options,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });

        $('.select2 span').addClass('needsclick')
    }

    $(document).on('click', '#add_new_contractor', function (event) {

        let name = $('.select2-search__field')[1].value;

        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/contractor/add_ajax',
            data: {
                name: name,
                account_id: $('#account_id').val()
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    let newOption = new Option(name, data.id, false, true);
                    $("#contractor_id").append(newOption).trigger('change');
                    $("#contractor_id").select2('close');
                } else {
                    alert(data.message);
                }
            }
        });
    });

    if ($("#app").length > 0) {
        $("#app").select2({
            placeholder: 'Заявка',
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/application/get_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: '#' + item.id + ' ' + item.product + ' - ' + item.amount + ' ' + item.currency,
                                options: item.options,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    }

    $('html').on('change keyup', '#search', function () {
        let query = $(this).val();
        if (query.length > 0) {
            $('.operation_tr').each(function (index) {
                let tr = $(this);
                let show = false;
                $(this).find('.filter_td').each(function (index) {
                    let td = $(this);
                    if (td.text().length > 0 && td.text().toLowerCase().indexOf(query.toLowerCase()) !== -1) {
                        show = true;
                        td.addClass('found');
                    } else {
                        td.removeClass('found');
                    }
                });
                if (show) {
                    tr.removeClass('hidden');
                } else {
                    tr.addClass('hidden');
                }
            });
        } else {
            $('.operation_tr').each(function (index) {
                $(this).removeClass('hidden');
                $(this).find('.filter_td').each(function (index) {
                    $(this).removeClass('found');
                });
            });
        }
    });

    //зміна дати в операціях
    $('html').on('change', 'table .date', function () {
        let input = $(this);
        let date = input.val();
        console.log(date);
        $.ajax({
            type: "POST",
            url: '/operation/change_date_in_operation_ajax/' + input.parents('tr').attr('data-operation_id'),
            data: {
                date: date
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {

                    location.reload();
                } else {
                    alert(data.message);
                }
            }
        });
    });

    $('html').on('change', '#credit', function () {
        if ($(this).prop("checked") === true) {
            $('.credit_block').show();
        } else {
            $('.credit_block').hide();
        }
    });

    // $('html').on('change', '.real_or_plan', function () {
    //     console.log($(this).val());
    //
    //     let op_type = $(this).val();
    //     let fields_for_real_operations = $(this).parents('form').find('.fields_for_real_operations');
    //     let fields_for_plan_operations = $(this).parents('form').find('.fields_for_plan_operations');
    //     let fields_for_template_operations = $(this).parents('form').find('.fields_for_template_operations');
    //
    //     if (op_type === 'plan') {
    //         fields_for_real_operations.hide();
    //         fields_for_template_operations.hide();
    //         fields_for_plan_operations.show();
    //     } else if (op_type === 'real') {
    //         fields_for_plan_operations.hide();
    //         fields_for_template_operations.hide();
    //         fields_for_real_operations.show();
    //     }else if (op_type === 'real') {
    //         fields_for_plan_operations.hide();
    //         fields_for_real_operations.show();
    //     }
    // });

    $(document).on('change', "[name=probability]", function () {
        $(this).parents('div').find('.probability_number').text($(this).val());
        $("#amount").val($(this).val());
    });

    /*************************************** plan ***********************************************************/
    $(document).on('change', '.is_shown', function (event) {
        let check = $(this);
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/operation/change_is_shown_ajax/' + check.parents('tr').attr('data-operation_id'),
            data: {
                is_shown: check.prop("checked") === true ? 1 : 0
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    location.reload();
                } else {
                    alert(data.message);
                }
            }
        });

    });

    $(document).on('click', '.perform', function (event) {
        let check = $(this);
        event.preventDefault();
        if (confirm("Ви впевнені, що хочете здійснити цю операцію? ")) {
            $.ajax({
                type: "POST",
                url: '/operation/perform_ajax/' + check.parents('tr').attr('data-operation_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    });

    $(document).on('click', '.notify', function (event) {
        let check = $(this);
        $.ajax({
            type: "POST",
            url: '/operation/change_notify_ajax/' + check.parents('tr').attr('data-operation_id'),
            data: {
                notify: check.prop("checked") === true ? 1 : 0
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    if (check.prop("checked") === true) {
                        notification('Успішно змінено. Нагадування в телеграм прийде за тиждень, 3 дні, і в день оплати');
                    } else {
                        notification('Успішно змінено. Нагадування вимкнено');
                    }
                } else {
                    alert(data.message);
                }
            }
        });
    });
});

// function matchContractor(params, data) {
//     // If there are no search terms, return all of the data
//     if ($.trim(params.term) === '') {
//         return data;
//     }
//
//     // Do not display the item if there is no 'text' property
//     if (typeof data.text === 'undefined') {
//         return null;
//     }
// // console.log(data);
//     // `params.term` should be the term that is used for searching
//     // `data.text` is the text that is displayed for the data object
//     if (data.text.indexOf(params.term) > -1) {
//         let modifiedData = $.extend({}, data, true);
//         modifiedData.text += ' (matched)';
//
//         // You can return modified objects from here
//         // This includes matching the `children` how you want in nested data sets
//         return modifiedData;
//     }
//
//     // Return `null` if the term should not be displayed
//     return null;
// }