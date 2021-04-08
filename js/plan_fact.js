jQuery(document).ready(function () {
    if ($('#plan_fact').length > 0) {
        calculate_margins('plan');
        calculate_margins('fact');

        let project_id = $('#project_id').val();

        if ($("#plan_fact #workers_for_plan_fact").length > 0) {
            $("#plan_fact #workers_for_plan_fact").select2({
                placeholder: 'Залучити спіробітника',
                customClass: "form-control",
                escapeMarkup: function (markup) {
                    return markup;
                },
                ajax: {
                    url: '/plan_fact/get_workers_ajax/' + project_id,
                    dataType: "json",
                    type: 'POST',
                    processResults: function (data) {
                        return {
                            results: $.map(data.workers, function (item) {
                                return {
                                    id: item.id,
                                    text: item.name,
                                    options: item.options,
                                    children: $.map(item.children, function (child) {
                                        return {
                                            id: child.id,
                                            text: child.name + ' ' + child.surname,
                                            options: child.options
                                        }
                                    })
                                }
                            })
                        };
                    }
                }
            });
        }
    }

    $('html').on('click', '.storage1_names_for_plan_fact', function (e) {
        let name = $(this).text();
        let id = $(this).attr('data_id');
        let price = $(this).attr('data_price');
        let amount = $(this).attr('data_amount');
        let unit = $(this).attr('data_unit');
        // let total = parseFloat(price * amount).toFixed(2);

        let tr = '<tr data-product_id="' + id + '">' +
            '<td class="id">' + id + '</td>' +
            '<td class="name">' + name + '</td>' +
            '<td><input type="number" name="amount" value="' + 0 + '" /> </td>' +
            '<td class="unit">' + unit + '</td>' +
            '<td class="price">' + price + '</td>' +
            '<td data-total="0" class="total">0 грн</td>' +
            '<td><img class="delete_product_from_plan_fact" src="../../img/trash-icon.jpg"/></td>' +
            '</tr>';

        $('.materials1_table tbody').append(tr);
        // update_products($('.plan .materials1_table'), 'plan', 'material_expenses');
        // update_products($('.fact .materials1_table'), 'fact', 'material_expenses');

        // $('.storage_purchase_table_block').animate({
        //     scrollTop: $('.storage_purchase_table_block').height()
        // }, 200);

        $(this).hide();
    });

    $('html').on('click', '.storage2_names_for_plan_fact', function (e) {
        let name = $(this).text();
        let id = $(this).attr('data_id');
        let price = $(this).attr('data_price');
        let amount = $(this).attr('data_amount');
        let unit = $(this).attr('data_unit');
        // let total = parseFloat(price * amount).toFixed(2);

        let tr = '<tr data-product_id="' + id + '">' +
            '<td class="id">' + id + '</td>' +
            '<td class="name">' + name + '</td>' +
            '<td><input type="number" name="amount" value="' + amount + '" /> </td>' +
            '<td class="unit">' + unit + '</td>' +
            '<td class="price">' + price + '</td>' +

            '<td data-total="0" class="total">0 грн</td>' +
            '<td><img class="delete_product_from_plan_fact" src="../../img/trash-icon.jpg"/></td>' +
            '</tr>'

        $('.materials2_table tbody').append(tr);

        // $('.storage_purchase_table_block').animate({
        //     scrollTop: $('.storage_purchase_table_block').height()
        // }, 200);

        $(this).hide();
    });

    $('html').on('click', '.workers_for_plan_fact', function (e) {
        // let name = $(this).text();
        let id = $(this).attr('data_id');
        let name = $(this).attr('data_name');
        let profession_id = $(this).attr('data_profession_id');
        let total = 0;


        let tr = '<tr data-pfw_id="" class="worker_payment" data-worker_id="' + id + '">' +
            '<td class="id">' + id + '</td>' +
            '<td class="name">' + name + '</td>' +
            '<td colspan="3"></td>' +
            '<td data-total="' + total + '" class="money_amount total">' + total + '</td>' +
            '<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>' +
            '</tr>';

        if (parseInt(profession_id) === 8 || parseInt(profession_id) === 18) {
            $('.salary_table tbody .workers_tr').after(tr);
        }

        if (parseInt(profession_id) === 7 || parseInt(profession_id) === 17 || parseInt(profession_id) === 9) {
            $('.salary_table tbody .brigadier_tr').after(tr);
        }
        if ($('#away').is(':checked')) {
            let day_price = 500;
            let travel_payment = parseInt($('#days_amount_plan').text()) * day_price;
            let tr2_plan = '<tr class="travel_payment" data-worker_id="' + id + '">' +
                '<td class="id">' + id + '</td>' +
                '<td class="name">' + name + '</td>' +
                '<td><input class="form-control payment" type="number" value="' + day_price + '"/></td>' +
                '<td>день</td>' +
                '<td><input class="form-control days_amount" value="' + $('#days_amount_plan').text() + '"/></td>' +
                '<td data-total="' + travel_payment + '" class="money_amount total">' + travel_payment + '</td>' +
                '<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>' +
                '</tr>';
            $('.plan .salary_table tbody .travel_payments_tr').after(tr2_plan);

            travel_payment = parseInt($('#days_amount_fact').text()) * day_price;
            let tr2_fact = '<tr class="travel_payment" data-worker_id="' + id + '">' +
                '<td class="id">' + id + '</td>' +
                '<td class="name">' + name + '</td>' +
                '<td><input class="form-control payment" type="number" value="' + day_price + '"/></td>' +
                '<td>день</td>' +
                '<td><input class="form-control days_amount" value="' + $('#days_amount_fact').text() + '"/></td>' +
                '<td data-total="' + travel_payment + '" class="money_amount total">' + travel_payment + '</td>' +
                '<td><img class="delete_worker_from_plan_fact" src="../../img/trash-icon.jpg"/></td>' +
                '</tr>';

            $('.fact .salary_table tbody .travel_payments_tr').after(tr2_fact);
            update_workers($('.plan .salary_table'), 'plan');
            update_workers($('.fact .salary_table'), 'fact');
        }

        $(this).hide();

    });
    //
    // $('html').on('change', '#start_date_plan, #end_date_plan', function (e) {
    //     let start_date = new Date($('#start_date_plan').val());
    //     let end_date = new Date($('#end_date_plan').val());
    //     let Difference_In_Time = end_date.getTime() - start_date.getTime();
    //     let Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24) + 1;
    //     let difference = Difference_In_Days > 0 ? Difference_In_Days : 0;
    //     $('#days_amount_plan').text(difference);
    //     update_param($(this).attr('data-param'), $(this).val(), 'plan');
    //     update_param('days_amount', difference, 'plan');
    //     calculate_margins('plan');
    // });

    $('html').on('change', '#start_date_fact, #end_date_fact, #start_date_plan, #end_date_plan', function (e) {
        let type = $(this).attr('data-type');
        let param = $(this).attr('data-param');
        let value = $(this).val();
        calculate_dates(type);
        update_param(param, value, type);
        calculate_margins(type);

        if ($('#by_plan').is(':checked') && type === 'plan') {
            $('#' + param + '_fact').val(value);
            calculate_dates('fact');
            update_param(param, value, 'fact');
            calculate_margins('fact');
        }
    });

    $('html').on('change', '#square_plan, #square_fact, #total_amount_plan, #total_amount_fact, #transport_total_plan, #agent_total_plan, #other_total_plan', function (e) {
        let type = $(this).attr('data-type');
        let param = $(this).attr('data-param');
        let value = $(this).val();
        let article_id = type === 'fact' ? $('#' + param + '_fact').attr('data-article_id') : 0;
        update_param(param, value, type, article_id);
        calculate_margins(type);
        if ($('#by_plan').is(':checked') && type === 'plan') {
            $('#' + param + '_fact').val(value);
            update_param(type, value, 'fact', article_id);
            calculate_margins('fact');
        }
    });


    $('html').on('change', '#responsible_id, #currency', function (e) {
        update_param($(this).attr('data-param'), $(this).val(), null);
    });

    $('html').on('change', '#away, #training', function (e) {
        let value = $(this).is(':checked') ? 1 : 0;
        update_param($(this).attr('data-param'), value, null);
        calculate_margins($(this).attr('data-type'));
    });

    // $('html').on('change', '#total_amount_plan', function (e) {
    //     if ($('#by_plan').is(':checked')) {
    //         $('#total_amount_fact').val($(this).val());
    //         update_param('total_amount', $(this).val(), 'fact');
    //         calculate_margins('fact');
    //     }
    // });

    // $('html').on('change', '#total_amount_fact', function (e) {
    //     let total = $(this).val();
    //     let cost = $('#cost_fact').text();
    //     let margin_amount = parseFloat(total) - parseFloat(cost);
    //     let margin = (parseFloat(margin_amount) / parseFloat(total)).toFixed(2) * 100;
    //     $('#margin_fact').text(margin_amount + ' грн - ' + margin + '%');
    // });

    $('html').on('click', '#create_materials_app', function (e) {
        e.preventDefault();

        let names = [];
        let btn = $(this);
        let storage_id = btn.attr('data-storage_id');
        let project_id = btn.attr('data-project_id');
        let author_id = btn.attr('data-project_id');
        let department_id = btn.attr('data-department_id');
        $('.plan .materials1_table tbody tr').each(function (index) {
            let id = $(this).attr('data-product_id');
            let amount = $(this).find('[name=amount]').val();
            names.push({
                id: id,
                amount: amount
            });
        });
        if (names.length > 0) {
            $.ajax({
                type: "POST",
                url: '/storage/add_app_ajax/' + storage_id,
                data: {
                    names: names,
                    author_id: author_id,
                    project_id: project_id,
                    department_id: department_id,
                    date_for: $('#start_date_plan').val(),
                    source: 'plan-fact_plan_materials_table'
                },
                success: function (data) {

                    data = JSON.parse(data);
                    let success = '<h3 class="success">Заявку ' + data.id + ' на склад створено.</span>';
                    open_popup(success);

                    btn.hide();
                }
            });
        } else {
            open_popup('<span class="error">Виберіть, будь ласка, хоча б одну позицію зі складу для формування заявки</span>')
        }
    });


    $('html').on('change', '.materials1_table [name=amount]', function (e) {
        e.preventDefault();
        let tr = $(this).parents('tr');
        let amount = parseFloat($(this).val());
        let type = $(this).parents('table').attr('data-type');
        let price = parseFloat(tr.find('.price').text());
        tr.find('.total').text(parseFloat(price * amount).toFixed(2) + ' грн');
        if (type === 'plan') {
            update_products($('.plan .materials1_table'), type, 'material_expenses');
            if ($('#by_plan').is(':checked')) {
                $('.fact .materials1_table tbody tr').each(function (index) {
                    if ($(this).attr('data-product_id') === tr.attr('data-product_id')) {
                        $(this).find('[name=amount]').val(amount);
                        $(this).find('.total').val(tr.find('[name=amount]').val());
                        $(this).find('.total').text(parseFloat(price * amount).toFixed(2) + ' грн');
                        update_products($('.fact .materials1_table'), 'fact', 'material_expenses');
                    }
                });
            }
        } else {
            update_products($('.fact .materials1_table'), type, 'material_expenses');
        }
        calculate_margins(type);
    });

    $('html').on('change', '.materials2_table [name=amount]', function (e) {
        e.preventDefault();
        let tr = $(this).parents('tr');
        let amount = parseFloat($(this).val());
        let type = $(this).parents('table').attr('data-type');
        let price = parseFloat(tr.find('.price').text());
        tr.find('.total').text(parseFloat(price * amount).toFixed(2) + ' грн');
        if (type === 'plan') {
            update_products($('.plan .materials2_table'), type, 'materials2_expenses');
            if ($('#by_plan').is(':checked')) {
                $('.fact .materials2_table tbody tr').each(function (index) {
                    if ($(this).attr('data-product_id') === tr.attr('data-product_id')) {
                        $(this).find('[name=amount]').val(amount);
                        $(this).find('.total').val(tr.find('[name=amount]').val());
                        $(this).find('.total').text(parseFloat(price * amount).toFixed(2) + ' грн');
                        update_products($('.fact .materials2_table'), 'fact', 'materials2_expenses');
                    }
                });
            }
        } else {
            update_products($('.fact .materials2_table'), type, 'materials2_expenses');
        }
        calculate_margins(type);
    });

    $('html').on('click', '#add_transport_operations', function (e) {
        let tr = '<tr class="transport_operation" >' +
            '<td class="id">#</td>' +
            // '<td class="date">' +
            // '<input type="date" class="form-control" value="' + $.datepicker.formatDate('yy-mm-dd', new Date()) + '"/>' +
            // '</td>' +
            '<td class="contractor">' +
            '<select class="form-control" id="contractor_id" name="contractor" required="">\' +\n' +
            '<option value="3">Калюжний</option>\' +\n' +
            '<option value="8">Шевченко</option>\' +\n' +
            '<option value="17">Потравко</option>\' +' +
            '</td>' +
            '<td class="amount">' +
            '<input type="number" class="form-control" \>' +
            '</td>' +
            '<td class="currency">' +
            '<select class="form-control" name="currency" required="">' +
            '<option value="UAH">₴</option>' +
            '<option value="USD">$</option>' +
            '<option value="EUR">€</option>' +
            '</select>' +
            '</td>' +
            '<td class="comment"><textarea class="form-control"></textarea></td>' +
            '<td><img class="save_transport_operation" src="../../img/done.jpg"/></td>' +
            '</tr>';
        $('.fact .transport_table tbody').append(tr);

    });

    $('html').on('click', '.save_transport_operation', function (e) {
        let btn = $(this);
        let tr = $(this).parents('tr');
        let errors = [];
        if (!(tr.find('.amount input').val() > 0)) {
            errors.push('Введіть, будь ласка, суму.')
        }
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/plan_fact/add_operation_ajax/' + $('[name=plan_fact_id]').val(),
                data: {
                    worker_id: tr.find('[name=contractor]').val(),
                    amount: tr.find('.amount input').val(),
                    currency: tr.find('.currency select').val(),
                    department_id: $('#department_id').val(),
                    date: $('#start_date_plan').val(),
                    comment: tr.find('.comment textarea').val(),
                    type: 'transport'
                },
                success: function (data) {
                    data = JSON.parse(data);
                    tr.find('.id').html(data.operation.id);
                    tr.find('[name=contractor]').hide().after('<span class="contractor">' + tr.find('[name=contractor] option:selected').text() + '</span>');
                    tr.find('.amount input').hide().after('<span class="amount">' + tr.find('.amount input').val() + '</span>');
                    tr.find('.currency select').hide().after('<span class="currency">' + tr.find('.currency select').val() + '</span>');
                    tr.find('.comment textarea').hide().after('<span class="comment">' + tr.find('.comment textarea').val() + '</span>');
                    btn.hide();
                    calculate_margins('fact');
                    notification('Зміни успішно збережено');
                }
            });
        } else {
            let error_html = '';
            $.each(errors, function (index, value) {
                error_html += '<span class="error">' + value + '</span>';
            });
            open_popup(error_html)

        }
    });

    $('html').on('click', '#add_other_operations', function (e) {
        e.preventDefault();
        let tr = '<tr class="other_operation" >' +
            '<td class="id">#</td>' +
            // '<td class="date">' +
            // '<input type="date" class="form-control" value="' + $.datepicker.formatDate('yy-mm-dd', new Date()) + '"/>' +
            // '</td>' +
            '<td class="contractor">' +
            '<select class="form-control" id="contractor_id" name="contractor" required="">\' +\n' +
            '<option value="3">Калюжний</option>\' +\n' +
            '<option value="8">Шевченко</option>\' +\n' +
            '<option value="17">Потравко</option>\' +' +
            '</td>' +
            '<td class="amount">' +
            '<input type="number" class="form-control" \>' +
            '</td>' +
            '<td class="currency">' +
            '<select class="form-control" name="currency" required="">' +
            '<option value="UAH">₴</option>' +
            '<option value="USD">$</option>' +
            '<option value="EUR">€</option>' +
            '</select>' +
            '</td>' +
            '<td class="comment"><textarea class="form-control"></textarea></td>' +
            '<td><img class="save_other_operation" src="../../img/done.jpg"/></td>' +
            '</tr>';
        $('.fact .other_table tbody').append(tr);
    });

    $('html').on('click', '.save_other_operation', function (e) {
        let btn = $(this);
        let tr = $(this).parents('tr');
        let errors = [];
        if (!(tr.find('.amount input').val() > 0)) {
            errors.push('Введіть, будь ласка, суму.')
        }
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/plan_fact/add_operation_ajax/' + $('[name=plan_fact_id]').val(),
                data: {
                    worker_id: tr.find('[name=contractor]').val(),
                    amount: tr.find('.amount input').val(),
                    currency: tr.find('.currency select').val(),
                    department_id: $('#department_id').val(),
                    date: $('#start_date_plan').val(),
                    comment: tr.find('.comment textarea').val(),
                    type: 'other'
                },
                success: function (data) {
                    data = JSON.parse(data);
                    tr.find('.id').html(data.operation.id);
                    tr.find('[name=contractor]').hide().after('<span class="contractor">' + tr.find('[name=contractor] option:selected').text() + '</span>');
                    tr.find('.amount input').hide().after('<span class="amount">' + tr.find('.amount input').val() + '</span>');
                    tr.find('.currency select').hide().after('<span class="currency">' + tr.find('.currency select').val() + '</span>');
                    tr.find('.comment textarea').hide().after('<span class="comment">' + tr.find('.comment textarea').val() + '</span>');
                    btn.hide();
                    calculate_margins('fact');
                    notification('Зміни успішно збережено');
                }
            });
        } else {
            let error_html = '';
            $.each(errors, function (index, value) {
                error_html += '<span class="error">' + value + '</span>';
            });
            open_popup(error_html)

        }
    });

    $('html').on('click', '.delete_product_from_plan_fact', function (e) {
        e.preventDefault();
        let btn = $(this);
        let tr = btn.parents('tr');
        if (confirm("Ви впевнені, що хочете видалити цей продукт з проекту? ")) {
            $.ajax({
                type: "POST",
                url: '/plan_fact/delete_product_ajax/' + btn.parents('tr').attr('data-pfp_id'),
                success: function (data) {
                    tr.remove();
                    notification('Продукт успішно видалено');
                }
            });
        }
    });

    $('html').on('click', '.delete_worker_from_plan_fact', function (e) {
        e.preventDefault();
        let btn = $(this);
        let tr = btn.parents('tr');
        let type = tr.parents('table').attr('data-type');
        let worker_id = btn.parents('tr').attr('data-pfw_id');
        console.log('.' + type + ' .salary_table');
        if (worker_id.length !== 0) {
            if (confirm("Ви впевнені, що хочете видалити цього працівника з проекту? ")) {
                $.ajax({
                    type: "POST",
                    url: '/plan_fact/delete_worker_ajax/' + worker_id,
                    success: function (data) {
                        tr.remove();
                        update_workers($('.' + type + ' .salary_table'), type);
                        notification('Продукт успішно видалено');
                    }
                });
            }
        } else {
            tr.remove();
            update_workers($('.' + type + ' .salary_table'), type);
        }
    });


});

function calculate_dates(type) {
    let start_date = new Date($('#start_date_' + type).val());
    let end_date = new Date($('#end_date_' + type).val());
    let Difference_In_Time = end_date.getTime() - start_date.getTime();
    let Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24) + 1;
    let difference = Difference_In_Days > 0 ? Difference_In_Days : 0;
    $('#days_amount_' + type).text(difference);
    update_param('days_amount', difference, type);

}

function calculate_margins(type = 'plan') { // рахує маржинальність по план факту
    let total_amount = $('#total_amount_' + type).val();
    let currency = $('#currency').val();
    let cost_0 = 0;
    $('.' + type + ' .materials_table tbody tr').each(function (index) {
        cost_0 += parseFloat($(this).find('.total').attr('data-total'));
    });

    $('.' + type + ' .salary_table tbody tr.travel_payment').each(function (index) {
        cost_0 += parseFloat($(this).find('.total').attr('data-total'));
    });

    cost_0 += parseFloat($('#transport_total_' + type).val());
    cost_0 += parseFloat($('#agent_total_' + type).val());
    cost_0 += parseFloat($('#other_total_' + type).val());

    let margin_0 = (parseFloat(total_amount) - parseFloat(cost_0)).toFixed(2);
    let margin_0_percent = (parseFloat(margin_0) / parseFloat(total_amount) * 100).toFixed(2);
    let worker_percent = (parseFloat(margin_0_percent) / 10).toFixed(1);

    let margin = margin_0;
    let cost = cost_0;
    $('.' + type + ' .salary_table tbody tr.worker_payment').each(function (index) {
        let payment = (parseFloat(margin_0) * parseFloat(worker_percent) / 100).toFixed(2);
        $(this).find('.total').attr('data-total', payment);
        $(this).find('.total').text(payment);
        cost += parseFloat(payment);
        margin -= parseFloat(payment);
    });

    let margin_percent = (parseFloat(margin) / parseFloat(total_amount) * 100).toFixed(1);

    $('#cost_' + type).text(moneyFormat(cost));
    $('#margin_' + type).text(moneyFormat(margin) + ' ' + currency + ' - ' + margin_percent + '%').change();
}

function update_param(param, value, type, article_id = 0) {
    $.ajax({
        type: "POST",
        url: '/plan_fact/update_params_ajax/' + $('[name=plan_fact_id]').val(),
        data: {
            param: param,
            value: value,
            type: type,  //plan,fact
            department_id: $('#department').val(),
            date: $('#start_date_plan').val(),
            article_id: article_id,
        },
        success: function (data) {
            // $('.notification.success').html('Зміни успішно збережено');
            // $('.notification.success').show();
            notification('Зміни успішно збережено');
        }
    });
}

function update_products(table, type, placement) {
    let products = [];
    table.find('tbody tr').each(function (index) {
        let id = $(this).attr('data-product_id');
        let amount = $(this).find('[name=amount]').val();
        products.push({
            id: id,
            amount: amount
        });
    });
    $.ajax({
        type: "POST",
        url: '/plan_fact/update_products_ajax/' + $('[name=plan_fact_id]').val(),
        data: {
            storage_id: table.attr('data-storage_id'),
            type: type,//plan,fact
            products: products,
            date: $('#start_date_plan').val(),
            placement: placement,
            article_id: table.attr('data-article_id'),
            department_id: $('#department').val(),
        },
        success: function (data) {
            notification('Зміни успішно збережено');
        }
    });
}

function update_workers(table, type) {
    let salary = [];
    let travel_payments = [];
    calculate_margins(type);
    table.find('tbody tr.worker_payment').each(function (index) {
        let id = $(this).attr('data-worker_id');
        let amount = $(this).find('.money_amount').text();
        salary.push({
            id: id,
            amount: amount
        });
    });
    table.find('tbody tr.travel_payment').each(function (index) {
        let id = $(this).attr('data-worker_id');
        let amount = $(this).find('.money_amount').text();
        travel_payments.push({
            id: id,
            amount: amount
        });
    });
    let workers = {salary: salary, travel_payments: travel_payments};
    $.ajax({
        type: "POST",
        url: '/plan_fact/update_workers_ajax/' + $('[name=plan_fact_id]').val(),
        data: {
            storage_id: table.attr('data-storage_id'),
            type: type,//plan,fact
            workers: workers,
            date: $('#start_date_plan').val(),
            department_id: $('#department').val(),

        },
        success: function (data) {
            notification('Зміни успішно збережено');
        }
    });
}

function moneyFormat(n) {
    return parseFloat(n).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").replace('.', ',');
}