jQuery(document).ready(function () {
    $(document).on('click', '.btn_add_project', function (event) {
        event.preventDefault();
        let errors = [];
        if ($('#project_sidenav form [name=department_id]').val() === '') {
            errors.push('Виберіть, будь ласка, департамент. Це потрібно, щоб правильно розподіляти розходи між різними структурами')
        }

        if ($('#project_sidenav [name=contractor_amount]').val() <= 0) {
            errors.push('Введіть, будь ласка, суму.')
        }

        if ($('#project_sidenav [name=start_date]').val().length <= 6) {
            errors.push('Введіть, будь ласка, дату старту проекту')
        }

        if ($('#project_sidenav [name=end_date]').val().length <= 6) {
            errors.push('Введіть, будь ласка, орієнтовну дату завершення проекту')
        }
        let form = $(this).closest('form');

        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/project/add_edit_ajax',
                data: form.serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    console.log(data.id);
                    if (data.status === 'ok') {
                        let success = '<h3 class="success">Проект успішно створено</h3></span>';
                        if (data.message === 'updated') {
                            success = '<h3 class="success">Проект успішно оновлено</h3></span>';
                        }
                        closeNav();
                        open_small_popup(success, 250, 200);
                        setTimeout(function () {
                            close_popup();
                        }, 3000);
                    } else {
                        alert(data.message);
                    }
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

    // $('html').on('click', '.combine', function (e) {
    //     e.preventDefault();
    //     var ids_str = '';
    //     $('.project_checkbox').each(function (index) {
    //         if ($(this).is(':checked')) {
    //             ids_str += $(this).parents('tr').attr('data-project_id') + ',';
    //         }
    //     });
    //     ids_str = ids_str.substring(0, ids_str.length - 1);
    //     console.log(ids_str);
    //     window.location = '/project/combine/' + ids_str;
    // });

    if ($("#contract_id").length > 0) {
        $("#contract_id").select2({
            placeholder: 'Договір',
            allowClear: true,
            // minimumInputLength: 2,
            customClass: "form-control",
            language: {
                inputTooShort: function () {
                    return 'Введіть мінімум 2 символи';
                },
                noResults: function () {
                    return '<button class="form-control" id="add_new_contract">Додати договір</a>';
                },
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/contract/get_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.number,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    }

    if ($("#project_sidenav form #project_sidenav_products").length > 0) {
        $("#project_sidenav form #project_sidenav_products").select2({
            placeholder: 'Продукти',
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
                url: '/sales/get_products_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    }

    if ($("#project_sidenav form #project_sidenav_observers").length > 0) {
        $("#project_sidenav form #project_sidenav_observers").select2({
            placeholder: 'Спостерігачі',
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
                url: '/user/get_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name + ' ' + item.surname,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    }

    if ($("#project_sidenav form #project_sidenav_storages").length > 0) {
        $("#project_sidenav form #project_sidenav_storages").select2({
            placeholder: 'Склади',
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
                url: '/storage/get_ajax',
                dataType: "json",
                type: 'POST',
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
    }

    $(document).on('click', '#add_new_contract', function (event) {

        let number = $('.select2-search__field')[0].value;
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/contract/add_ajax',
            data: {
                number: number
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    let newOption = new Option(number, data.id, false, true);
                    $("#project_sidenav #contract_id").append(newOption).trigger('change');
                    $("#project_sidenav #contract_id").select2('close');
                } else {
                    alert(data.message);
                }
            }
        });
    });
});

function fill_project_sidenav(data = null) {
    if (data !== null) {
        fill_project_sidenav();
        $('#project_sidenav #title').text('Редагувати проект');
        $.each(data, function (param, value) {
            if (param.indexOf('date') + 1 > 0) {
                value = data['human_' + param];
            }
            $('#project_sidenav [name=' + param + ']').val(value).change();
            //todo нижче код не працює, якщо вибрано більше ніж 1 значення
            if (param === 'products') {
                if (value.length > 0) {
                    $('#project_sidenav #project_sidenav_products').select2("trigger", "select", {
                        data: {id: value[0].id, text: value[0].name}
                    });
                }
            }
            if (param === 'observers') {
                if (value.length > 0) {
                    $('#project_sidenav #project_sidenav_observers').select2("trigger", "select", {
                        data: {id: value[0].id, text: value[0].name + '' + value[0].surname}
                    });
                }
            }

            if (param === 'storages') {
                if (value.length > 0) {
                    $('#project_sidenav #project_sidenav_storages').select2("trigger", "select", {
                        data: {id: value[0].id, text: value[0].name}
                    });
                }
            }
        });
    } else {
        $('#project_sidenav #title').text('Додати проект');
        $('#project_sidenav [name=author_id]').val('');
        $('#project_sidenav [name=name]').val('');
        $('#project_sidenav [name=department_id]').val('');
        $('#project_sidenav [name=start_date]').val($('#project_sidenav [name=start_date]').attr('data-default'));
        $('#project_sidenav [name=end_date]').val($('#project_sidenav [name=end_date]').attr('data-default'));
        $('#project_sidenav [name=status]').val('new');
        $('#project_sidenav [name=contract_amount]').val('');
        $('#project_sidenav [name=contract_currency]').val('UAH');
        $('#project_sidenav [name=contract_type_id]').val('1');
        $('#project_sidenav [name=contract_id]').val([]).change();
        $('#project_sidenav #project_sidenav_products').val([]).change();
        $('#project_sidenav #project_sidenav_observers').val([]).change();
        $('#project_sidenav #project_sidenav_storages').val([]).change();
        $('#project_sidenav [name=comment]').val('');
    }
}