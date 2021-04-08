$(document).ready(function () {

    $('html').on('click', '.operation_sidenav_btns .btn', function (e) {
        e.preventDefault();
        $('#operation_sidenav .panel').hide();
        $('.operation_sidenav_btns .btn').removeClass('btn-dark');
        $(this).addClass('btn-dark');
        let panel = $(this).attr('href').substr(1);
        $('#panel_' + panel).show();
    });

    /************************************ доходи ********************************************/
    //operation_add_income -  додати прихід
    $(document).on('click', '#operation_sidenav #panel_income  .btn_add_income', function (event) {
        event.preventDefault();
        let errors = [];

        $('#panel_income .error_span').remove();
        $('#panel_income .error').removeClass('error');

        if ($('#panel_income [name=amount]').val() <= 0) {
            errors.push({
                element: $('#panel_income [name=amount]'),
                message: 'Введіть, будь ласка, корректну суму.'
            });
        }

        if ($('#panel_income [name=comment]').val().length <= 3) {
            errors.push({
                element: $('#panel_income [name=comment]'),
                message: 'Введіть, будь ласка, корректний коментар. Він має бути не коротший 3 символів'
            });
        }

        let form = $(this).closest('form');
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/operation/add_ajax',
                data: form.serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        closeNav();
                        clear_sidenav();
                        let success = '<h3 style="margin-top: 65px;" class="success">Операцію успішно створено</h3>';
                        open_small_popup(success, 300, 200);
                        if (location.pathname === '/operation') {
                            location.reload();
                        }
                    } else {
                        alert(data.message);
                    }
                }
            });
        } else {
            $.each(errors, function (index, error) {
                error.element.addClass('error');
                error.element.parents('.element').append('<span class="error_span">' + error.message + '</span>');
            });
        }
    });

    //operation_add_income
    // $('html').on('change', '#container_operation_add_income #wallet_2_id', function () {
    //     let user_id = $('#wallet_2_id option:selected').attr('data_user_id');
    //     $('[name=user_id]').val(user_id);
    // });

    if ($("#operation_sidenav #panel_income [name=contractor_id]").length > 0) {
        $("#operation_sidenav #panel_income [name=contractor_id]").select2({
            placeholder: 'Клієнт',
            customClass: "form-control",
            language: {
                inputTooShort: function () {
                    return 'Введіть мінімум 2 символи';
                },
                noResults: function () {
                    // return '<button class="form-control" id="add_new_contractor">Додати клієнта</a>';
                    return '<button data-selector="#operation_sidenav #panel_income [name=contractor_id]" data-contractor_type="client" ' +
                        'class="form-control add_contractor_from_select2">Додати клієнта</a>';
                },
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/contractor/search_ajax/client',
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
    }

    if ($("#operation_sidenav #panel_income [name=article_id]").length > 0) {
        $("#operation_sidenav #panel_income [name=article_id]").select2({
            placeholder: 'Стаття доходів',
            customClass: "form-control",
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/articles/search_ajax/income',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
                                options: item.options,
                                children: $.map(item.children, function (child) {
                                    return {
                                        id: child.id,
                                        text: child.name,
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

    if ($("#operation_sidenav #panel_income [name=project_id]").length > 0) {
        $("#operation_sidenav #panel_income [name=project_id]").select2({
            placeholder: 'Проект',
            customClass: "form-control",
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/project/search_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
                                options: item.options,
                                children: $.map(item.children, function (child) {
                                    return {
                                        id: child.id,
                                        text: child.name,
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

    /************************************ витрати ********************************************/

    //operation_add_expenses -  додати витрату
    $(document).on('click', '.btn_add_expense', function (event) {
        event.preventDefault();

        let errors = [];
        // let items = [];
        // let items_comment = '';

        $('#panel_expense .error_span').remove();
        $('#panel_expense .error').removeClass('error');

        if ($('#panel_expense [name=amount]').val() <= 0) {
            errors.push({
                element: $('#panel_expense [name=amount]'),
                message: 'Введіть, будь ласка, корректну суму.'
            });
        }

        if ($('#panel_expense [name=comment]').val().length <= 3) {
            errors.push({
                element: $('#panel_expense [name=comment]'),
                message: 'Введіть, будь ласка, корректний коментар. Він має бути не коротший 3 символів'
            });
        }

        // if ($('#storage_purchase_show').prop("checked") === true) { // якщо це закупка на склад
        //     if ($('.storage_purchase #storage').val() === '') {
        //         errors.push('Виберіть, будь ласка, склад, на який робиться закупка');
        //     }
        //
        //     let operation_amount = parseFloat($('#container_operation_add_expenses [name=total_uah]').val());
        //     let total = 0;
        //
        //     $('#storage_purchase_table tbody tr').each(function (index) {
        //         let id = $(this).attr('data_name_id');
        //         let price = $(this).find('[name=price]').val();
        //         let amount = $(this).find('[name=amount]').val();
        //         let name = $(this).find('.name').text();
        //         let unit = $(this).find('.unit').text();
        //         let total = $(this).find('.total').text();
        //         let item = {
        //             id: id,
        //             price: price,
        //             amount: amount
        //         };
        //         items.push(item);
        //         items_comment += name + ' (' + price + ' грн) - ' + amount + ' ' + unit + ' - ' + total + ' грн' + " \r\n";
        //         total = parseFloat(parseFloat(total) + parseFloat(price * amount)).toFixed(2);
        //     });
        //
        //     if (operation_amount < total) {
        //         errors.push('Сума по операції в гривні - ' + operation_amount + ' грн<br/>Загальна сума закуплених товарів - ' + total + ' грн<br/> Сума по операції не може бути менше суми закуплених товарів')
        //     }
        // }

        // let data = {
        //     user_id: $('#panel_expense [name=user_id]').val(),
        //     wallet_1_id: $('#panel_expense [name=wallet_1_id]').val(),
        //     amount: $('#panel_expense [name=amount]').val(),
        //     currency: $('#panel_expense [name=currency]').val(),
        //     contractor_id: $('#panel_expense [name=contractor_id]').val(),
        //     contractor_type: $('#panel_expense [name=contractor_type]').val(),
        //     contractor_new: $('#panel_expense [name=contractor_new]').val(),
        //     project_id: $('#panel_expense [name=project_id]').val(),
        //     app_id: $('#panel_expense [name=app_id]').val(),
        //     comment: $('#panel_expense [name=comment]').val(),
        //     // storage_purchase_show: ($('[name=storage_purchase_show]').prop("checked") == true) ? true : false,
        //     storage_id: $('#panel_expense [name=storage_id]').val(),
        //     // items: items,
        //     // items_comment: items_comment
        // };
        // if ($('#is_planned').prop("checked") === true) {
        //     data.plan_date = $('[name=plan_date]').val();
        //     data.is_planned = true;
        // } else {
        //     data.date = $('[name=date]').val();
        // }
        let form = $(this).closest('form');
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/operation/add_ajax',
                // data: form.serialize() + '&' + $.param(data),
                // data: data,
                data: form.serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        closeNav();
                        clear_sidenav();
                        let success = '<h3 style="margin-top: 65px;" class="success">Операцію успішно створено</h3>';
                        open_small_popup(success, 300, 200);
                        if (location.pathname === '/operation') {
                            location.reload();
                        }
                    } else {
                        alert(data.message);
                    }
                }
            });
        } else {
            $.each(errors, function (index, error) {
                error.element.addClass('error');
                error.element.parents('.element').append('<span class="error_span">' + error.message + '</span>');
            });
        }
    });

    if ($("#operation_sidenav #panel_expense [name=contractor_id]").length > 0) {
        $("#operation_sidenav #panel_expense [name=contractor_id]").select2({
            placeholder: 'Поставщик чи підрядник',
            customClass: "form-control",
            language: {
                inputTooShort: function () {
                    return 'Введіть мінімум 2 символи';
                },
                noResults: function () {
                    // return '<button class="form-control" id="add_new_contractor">Додати контрагента</a>';
                    return '<button data-selector="#operation_sidenav #panel_expense [name=contractor_id]" data-contractor_type="provider" ' +
                        'class="form-control add_contractor_from_select2">Додати контрагента</a>';
                },
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/contractor/search_ajax/provider',
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
    }

    if ($("#operation_sidenav #panel_expense [name=app_id]").length > 0) {
        $("#operation_sidenav #panel_expense [name=app_id]").select2({
            placeholder: 'Заявка',
            escapeMarkup: function (markup) {
                return markup;
            },
            allowClear: true,
            ajax: {
                url: '/application/get_user_apps_ajax/' + $('#user_id').val(),
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

    if ($("#operation_sidenav #panel_expense [name=article_id]").length > 0) {
        $("#operation_sidenav #panel_expense [name=article_id]").select2({
            placeholder: 'Стаття розходів',
            customClass: "form-control",
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/articles/search_ajax/expense',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
                                options: item.options,
                                children: $.map(item.children, function (child) {
                                    return {
                                        id: child.id,
                                        text: child.name,
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

    if ($("#operation_sidenav #panel_expense [name=project_id]").length > 0) {
        $("#operation_sidenav #panel_expense [name=project_id]").select2({
            placeholder: 'Проект',
            customClass: "form-control",
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/project/search_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
                                options: item.options,
                                children: $.map(item.children, function (child) {
                                    return {
                                        id: child.id,
                                        text: child.name,
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

    /************************************ переміщення ********************************************/
    if ($("#operation_sidenav #panel_transfer [name=user_2_id]").length > 0) {

        let current_user_id = $('[name=user_id]').val();
        $("#operation_sidenav #panel_transfer [name=user_2_id]").select2({
            placeholder: 'Співробітник',
            customClass: "form-control",
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/user/get_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            if (item.id === current_user_id) {
                                return {
                                    id: item.id,
                                    text: 'Самому собі',
                                    options: item.options
                                }
                            } else {
                                return {
                                    id: item.id,
                                    text: item.name + ' ' + item.surname,
                                    options: item.options
                                }
                            }
                        })
                    }
                }
            }
        });
    }

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


    //operation_add_transfer -  додати переміщення
    $(document).on('click', '.btn_add_transfer', function (event) {
        event.preventDefault();
        let errors = [];

        // if ($('#operation_sidenav #panel_transfer [name=amount]').val() <= 0) {
        //     errors.push('Введіть, будь ласка, корректну суму.')
        // }
        if ($('#operation_sidenav #panel_transfer [name=amount]').val() <= 0) {
            errors.push({
                element: $('#operation_sidenav #panel_transfer [name=amount]'),
                message: 'Введіть, будь ласка, корректну суму.'
            });
        }

        // if ($('#operation_sidenav #panel_transfer [name=comment]').val().length <= 3) {
        //     errors.push('Введіть, будь ласка, корректний коментар. Він має бути не коротший 3 символів')
        // }

        if ($('#operation_sidenav #panel_transfer [name=comment]').val().length <= 3) {
            errors.push({
                element: $('#operation_sidenav #panel_transfer [name=comment]'),
                message: 'Введіть, будь ласка, корректний коментар. Він має бути не коротший 3 символів'
            });
        }

        let form = $(this).closest('form');
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/operation/add_ajax',
                data: form.serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        closeNav();
                        clear_sidenav();
                        let success = '<h3 style="margin-top: 65px;" class="success">Операцію успішно створено</h3>';
                        open_small_popup(success, 300, 200);
                        if (location.pathname === '/operation') {
                            location.reload();
                        }
                    } else {
                        alert(data.message);
                    }
                }
            });
        // } else {
        //     let error_html = '';
        //     $.each(errors, function (index, value) {
        //         error_html += '<span class="error">' + value + '</span>';
        //     });
        //     open_popup(error_html)
        // }
        } else {
            $.each(errors, function (index, error) {
                error.element.addClass('error');
                error.element.parents('.element').append('<span class="error_span">' + error.message + '</span>');
            });
        }
    });

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


    // $(document).on('click', '#add_new_contractor', function (event) {
    //
    //     let name = $('.select2-search__field')[1].value;
    //
    //     event.preventDefault();
    //     $.ajax({
    //         type: "POST",
    //         url: '/contractor/add_ajax',
    //         data: {
    //             name: name,
    //             account_id: $('#account_id').val()
    //         },
    //         success: function (data) {
    //             data = JSON.parse(data);
    //             if (data.status === 'ok') {
    //                 let newOption = new Option(name, data.id, false, true);
    //                 $("#contractor_id").append(newOption).trigger('change');
    //                 $("#contractor_id").select2('close');
    //             } else {
    //                 alert(data.message);
    //             }
    //         }
    //     });
    // });

    if ($("#operation_sidenav #panel_transfer [name=app_id]").length > 0) {
        $("#operation_sidenav #panel_transfer [name=app_id]").select2({
            placeholder: 'Заявка',
            escapeMarkup: function (markup) {
                return markup;
            },
            allowClear: true,
            ajax: {
                url: '/application/get_user_apps_ajax/' + $('#user_id').val(),
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

    if ($("#operation_sidenav #panel_transfer [name=project_id]").length > 0) {
        $("#operation_sidenav #panel_transfer [name=project_id]").select2({
            placeholder: 'Проект',
            customClass: "form-control",
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/project/search_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: item.name,
                                options: item.options,
                                children: $.map(item.children, function (child) {
                                    return {
                                        id: child.id,
                                        text: child.name,
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
    // $('html').on('change keyup', '#search', function () {
    //     let query = $(this).val();
    //     if (query.length > 0) {
    //         $('.operation_tr').each(function (index) {
    //             let tr = $(this);
    //             let show = false;
    //             $(this).find('.filter_td').each(function (index) {
    //                 let td = $(this);
    //                 if (td.text().length > 0 && td.text().toLowerCase().indexOf(query.toLowerCase()) !== -1) {
    //                     show = true;
    //                     td.addClass('found');
    //                 } else {
    //                     td.removeClass('found');
    //                 }
    //             });
    //             if (show) {
    //                 tr.removeClass('hidden');
    //             } else {
    //                 tr.addClass('hidden');
    //             }
    //         });
    //     } else {
    //         $('.operation_tr').each(function (index) {
    //             $(this).removeClass('hidden');
    //             $(this).find('.filter_td').each(function (index) {
    //                 $(this).removeClass('found');
    //             });
    //         });
    //     }
    // });

    // ******************************************************************************************************************/

    //зміна дати в операціях
    $('html').on('change', '.change_operation_date', function () {
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

    $('html').on('change', '.real_or_plan', function () {
        let op_type = $(this).val();
        let fields_for_real_operations = $(this).parents('form').find('.fields_for_real_operations');
        let fields_for_plan_operations = $(this).parents('form').find('.fields_for_plan_operations');
        let fields_for_template_operations = $(this).parents('form').find('.fields_for_template_operations');

        if (op_type === 'plan') {
            fields_for_real_operations.hide();
            fields_for_template_operations.hide();
            fields_for_plan_operations.show();
        } else if (op_type === 'real') {
            fields_for_plan_operations.hide();
            fields_for_template_operations.hide();
            fields_for_real_operations.show();
        } else if (op_type === 'template') {
            fields_for_plan_operations.hide();
            fields_for_real_operations.hide();
            fields_for_template_operations.show();
        }
    });

    $(document).on('change', "[name=probability]", function () {
        $(this).parents('div').find('.probability_number').text($(this).val());
        $("#amount").val($(this).val());
    });

    $(document).on('change', "[name=has_end_date]", function () {
        let checkbox = $(this);
        let date_input = checkbox.parents('.element').find('.repeat_end_date');
        if (checkbox.prop("checked") === true) {
            date_input.show();
        } else {
            date_input.hide();
        }
    });
});


function clear_sidenav() {
    $('#operation_sidenav [name=amount]').val('');
    $('#operation_sidenav [name=comment]').val('');
}

function fill_operation_sidenav(data = null) {
    if (data !== null) {
        $('#operation_sidenav .title').text('Редагувати операцію');
        let selector = '';
        switch (data.operation_type_id) {
            case '1'://доходи
                selector = 'income';
                $('.operation_sidenav_btns > #operation_sidenav_open_income').trigger('click');
                break;
            case '2'://витрати
                selector = 'expense';
                $('.operation_sidenav_btns > #operation_sidenav_open_expense').trigger('click');
                break;
            case '3'://переміщення
                selector = 'transfer';
                $('.operation_sidenav_btns > #operation_sidenav_open_transfer').trigger('click');
                break;
        }

        // switch (data.time_type) {
        //     case 'real'://здійснені
        console.log('#' + selector + '_' + data.time_type);
        $('#' + selector + '_' + data.time_type).trigger('click');
        //         break;
        //     case 'plan'://плановані
        //         selector = '#panel_expense';
        //         $('.operation_sidenav_btns > #operation_sidenav_open_expense').trigger('click');
        //         break;
        //     case 'template'://шаблони
        //         selector = '#panel_transfer';
        //         $('.operation_sidenav_btns > #operation_sidenav_open_transfer').trigger('click');
        //         break;
        // }

        $('#operation_sidenav #title').text('Редагувати проект');
        $.each(data, function (param, value) {
            if (param.indexOf('date') + 1 > 0) {
                value = data['human_' + param];
            }

            $('#operation_sidenav #panel_' + selector + ' [name=' + param + ']').val(value).change();
            if (param.substr(param.length - 3) === '_id') {
                let param_key = param.substr(0, param.length - 3);
                if (['app', 'contractor', 'article', 'project'].indexOf(param_key) !== -1) {
                    $('#operation_sidenav #panel_' + selector + ' [name=' + param_key + '_id]').select2("trigger", "select", {
                        data: {id: data[param_key + '_id'], text: data[param_key + '_name']}
                    });
                }
            }

        });
    } else {
        $('#operation_sidenav .title').text('Додати операцію');

        $('#operation_sidenav [name=contractor_id]').val([]).change();
        $('#operation_sidenav [name="wallet_id"]').prop("selectedIndex", 0);
        $('#operation_sidenav [name="user_2_id"]').prop("selectedIndex", 0);
        $('#operation_sidenav [name="wallet_2_id"]').prop("selectedIndex", 0);
        $('#operation_sidenav [name=amount]').val('');
        $('#operation_sidenav [name="rate"]').val('');
        $('#operation_sidenav [name="amount2"]').val('');
        $('#operation_sidenav [name=article_id]').val([]).change();
        $('#operation_sidenav [name="app_id"]').val([]).change();

        $('#income_real').trigger('click');
        $('#expense_real').trigger('click');
        $('#transfer_real').trigger('click');

        $('#operation_sidenav [name="date"]').val($('#operation_sidenav [name="date"]').attr('data-default'));
        $('#operation_sidenav [name="planned_on"]').val($('#operation_sidenav [name="planned_on"]').attr('data-default'));
        $('#operation_sidenav [name="repeat_start_date"]').val($('#operation_sidenav [name="repeat_start_date"]').attr('data-default'));
        $('#operation_sidenav [name="repeat_end_date"]').val($('#operation_sidenav [name="repeat_end_date"]').attr('data-default'));

        $('#operation_sidenav [name=project_id]').val('');

        $('#operation_sidenav [name="credit"]').each(function (index) {
            if ($(this).prop("checked") === true) {
                $(this).trigger('click');
            }
        });

        $('#operation_sidenav [name="сredit_commision_amount"]').val('');
        $('#operation_sidenav [name="сredit_commision_currency"]').val('UAH');
        $('#operation_sidenav [name="сredit_payment_date"]').val($('#operation_sidenav [name="сredit_payment_date"]').attr('data-default'));

        $('#operation_sidenav [name=comment]').val('').change();
    }
}