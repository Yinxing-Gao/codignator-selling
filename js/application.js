$(document).ready(function () {
    if ($("#application_add [name=project_id]").length > 0) {
        $("#application_add [name=project_id]").select2({
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

    if ($("#application_add [name=article_id]").length > 0) {
        $("#application_add [name=article_id]").select2({
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

    if ($("#application_add [name=authority_id]").length > 0) {
        $("#application_add [name=authority_id]").select2()
    }

    if ($("#application_add [name=contractor_id]").length > 0) {
        $("#application_add [name=contractor_id]").select2({
            placeholder: 'Поставщик чи підрядник',
            customClass: "form-control",
            language: {
                inputTooShort: function () {
                    return 'Введіть мінімум 2 символи';
                },
                noResults: function () {
                    return '<button data-selector="#application_add [name=contractor_id]" data-contractor_type="provider"' +
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

    $(window).scroll(function () {
        if ($('.static_menu').length > 0) {
            if ($(window).scrollTop() >= $('.static_menu').offset().top) {
                if ($('.fixed_menu').hasClass('fixed') === false) {
                    $('.fixed_menu').addClass('fixed');
                    $('.fixed_container').addClass('container');
                }
            } else {
                if ($('.fixed_menu').hasClass('fixed') === true) {
                    $('.fixed_menu').removeClass('fixed');
                    $('.fixed_container').removeClass('container');
                }
            }
        }
    });


    //application_add
    $(document).on('click', '.btn_send_app', function (event) {
        event.preventDefault();
        let errors = [];
        // if ($('#department').val() === '') {
        //     errors.push('Виберіть, будь ласка, департамент. Це потрібно, щоб правильно розподіляти розходи між різними структурами')
        // }
        if ($('#application_add [name=amount]').val() <= 0) {
            errors.push('Введіть, будь ласка, суму.')
        }

        if ($('#application_add [name=date_for]').val().length !== 10) {
            errors.push('Введіть, будь ласка, дату на коли потрібно, щоб гроші уже були виділені')
        }

        if ($('#application_add [name=product]').val().length <= 3) {
            errors.push('Введіть, будь ласка, назву так, щоб її було достатньо для того, щоб ідентифікувати цю заявку в загальному списку. Якщо ви не можете знайти підходящу статтю - зверніться до адміністратора')
        }

        // if ($('#application_add [name=article_id]').val() === '') {
        //     errors.push('Введіть, будь ласка, статтю витрат по якій пройде ця заявка. Це потрібно для структуризації розходів')
        // }

        if ($('#application_add [name=situation]').val().length < 10) {
            errors.push('Опишіть ситуацію конкретніше, будь ласка')
        }

        if ($('#application_add [name=data]').val().length < 20) {
            errors.push("Опишіть дані конкретніше, будь ласка. тут треба МАКСИМАЛЬНО детально описати всі дані, на основі яких прийнято рішення про потребу в фінансуванні. Наприклад, якщо це деталі на апарат, то бажено вказати дату, коли апарат повинен бути готовим, тривалість доставки і т.д.Це дані повинні дати вичерпну відповідь на запитання \'чому це треба купити взагалі?\' і \'чому це треба придбати саме зараз?\'");
        }

        if ($('#application_add [name=decision]').val().length < 5) {
            errors.push('Введіть рішення повністю, будь ласка')
        }
        let action = $(this).attr('data-action');

        let form = $(this).closest('form');

        if (errors.length === 0) {
            if (action === "add") {
                $.ajax({
                    type: "POST",
                    url: '/application/add_ajax',
                    data: form.serialize(),
                    success: function (data) {
                        data = JSON.parse(data);
                        console.log(data.id);
                        if (data.status === 'ok') {
                            let success = '<h3 class="success">Заявку успішно створено</h3><span class="normal">Номер вашої заявки<br/></span><span class="number">' + data.id + '</span> <span class="normal">Зараз вас перенаправить на список ваших заявок</span>';
                            open_popup(success);
                            setTimeout(function () {
                                window.location = '/application';
                            }, 3000);
                        } else {
                            alert(data.message);
                        }
                    }
                });
            } else {
                if (action === "edit") {
                    let app_id = $('[name=app_id]').val();
                    $.ajax({
                        type: "POST",
                        url: '/application/edit_ajax',
                        data: form.serialize(),
                        success: function (data) {
                            data = JSON.parse(data);
                            console.log(data.id);
                            if (data.status === 'ok') {
                                let success = '<h3 class="success">Заявку ' + app_id + ' успішно змінено</h3><span class="normal">Зараз вас перенаправить на список ваших заявок</span>';
                                open_popup(success);
                                setTimeout(function () {
                                    window.location = '/';
                                }, 3000);
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                }
            }
        } else {
            let error_html = '';
            $.each(errors, function (index, value) {
                error_html += '<span class="error">' + value + '</span>';
            });
            open_small_popup(error_html, 600, 350);
        }
    });

    let files; // переменная. будет содержать данные файлов

// заполняем переменную данными, при изменении значения поля file
    $('input[type=file]').on('change', function (event) {
        files = this.files;

        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

        upload_files(files, '/application/upload_order_ajax');
    });
    // обработка и отправка AJAX запроса при клике на кнопку upload_files
    // $('.upload_files').on('click', function (event) {
    //
    //     event.stopPropagation(); // остановка всех текущих JS событий
    //     event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега
    //
    //     upload_files( files,'/application/upload_order_ajax')
    // });

    let panel = 'all';
    //application_list
    if ($("div").is("#application_list")) {
        create_numbers('all');
        count_total('all');
        count_total_checked('all');

        $('html').on('click', '.type_btns .btn', function () {
            $('.panel').hide();
            panel = $(this).attr('href').substr(1);
            $('#panel_' + panel).show();

            setLocation('#' + panel);
            create_numbers(panel);
            count_total(panel);
            count_total_checked(panel);
        });

        $('html').on('click', '[type=checkbox].row_checkbox', function () {
            count_total_checked(panel);
        });

        $('html').on('click', '[type=checkbox].category_switch', function () {
            let cat = $(this).attr('data-id');
            let rows = $('.app_row_cat_' + cat);
            console.log('.app_row_cat_' + cat);
            if (!$(this).is(':checked')) {
                rows.hide();
            }
            if ($(this).is(':checked')) {
                rows.show();
            }
        });

        $('html').on('change', '.category_select', function () {
            let select = $(this);
            $.ajax({
                type: "POST",
                url: '/application/change_category_ajax/' + $(this).parents('tr').attr('data-app-id'),
                data: {
                    category: $(this).val(),
                },
                success: function (data) {
                    data = JSON.parse(data);
                    select.parents('tr').attr('class', '');
                    select.parents('tr').addClass('app_row_cat_' + select.val())
                }
            });
        });

        $('html').on('click', '.approve', function (e) {
            e.preventDefault();
            let btn = $(this);
            console.log('test');
            let id_arr = [];
            $('.row_checkbox').each(function (index) {
                if ($(this).is(':checked')) {
                    id_arr.push($(this).parents('tr').attr('data-app-id'));
                }
            });

            $.ajax({
                type: "POST",
                url: '/application/approve_ajax',
                data: {
                    id_arr: JSON.stringify(id_arr),
                    user_id: $('[name=user_id]').val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        });

        $('html').on('click', '.transfer_to_pay', function (e) {
            e.preventDefault();
            let btn = $(this);
            let id_arr = [];
            $('.row_checkbox').each(function (index) {
                console.log($(this).parents('tr').find('.amount_to_pay'));
                if ($(this).is(':checked')
                    || ($(this).parents('tr').find('.amount_to_pay').length > 0 && $(this).parents('tr').find('.amount_to_pay').val().length > 0)) {
                    id_arr.push(
                        {
                            id: $(this).parents('tr').attr('data-app-id'),
                            amount: $(this).parents('tr').find('.amount_to_pay').val(),
                            comment: $(this).parents('tr').find('.director_comment span').text(),
                        });
                }
            });
            $.ajax({
                type: "POST",
                url: '/application/transfer_to_pay_ajax',
                data: {
                    id_arr: JSON.stringify(id_arr),
                    user_id: $('[name=user_id]').val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        });

        $('html').on('click', '.pay', function (e) {
            e.preventDefault();
            let btn = $(this);
            let id_arr = [];
            $('.row_checkbox').each(function (index) {
                if ($(this).is(':checked')
                    || ($(this).parents('tr').find('.amount_to_pay').length > 0 && $(this).parents('tr').find('.amount_to_pay').val().length > 0)) {
                    id_arr.push(
                        {
                            id: $(this).parents('tr').attr('data-app-id'),
                            amount: $(this).parents('tr').find('.amount_to_pay').val()
                        });
                }
            });
            $.ajax({
                type: "POST",
                url: '/application/pay_ajax',
                data: {
                    id_arr: JSON.stringify(id_arr),
                    user_id: $('[name=user_id]').val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        });

        $('html').on('click', '.check_as_payed', function (e) {
            e.preventDefault();
            let btn = $(this);
            let id_arr = [];
            $('.row_checkbox').each(function (index) {
                if ($(this).is(':checked')) {
                    id_arr.push($(this).parents('tr').attr('data-app-id'));
                }
            });
            $.ajax({
                type: "POST",
                url: '/application/check_as_payed_ajax',
                data: {
                    id_arr: JSON.stringify(id_arr),
                    user_id: $('[name=user_id]').val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        });

        $('html').on('click', '.reject', function (e) {
            e.preventDefault();
            let btn = $(this);
            let id_arr = [];
            $('.row_checkbox').each(function (index) {
                if ($(this).is(':checked')) {
                    id_arr.push($(this).parents('tr').attr('data-app-id'));
                }
            });
            $.ajax({
                type: "POST",
                url: '/application/reject_ajax',
                data: {
                    id_arr: JSON.stringify(id_arr),
                    user_id: $('[name=user_id]').val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        });


        $(document).on('click', '.app_info', function (event) {
            event.preventDefault();
            let id = $(this).attr('data-app_info');
            $.ajax({
                type: "POST",
                url: '/application/info_ajax/' + id,
                success: function (data) {
                    data = JSON.parse(data);
                    console.log(data);
                    open_popup(data);
                }
            });
        });

        $('html').on('click', '.delete_application', function (e) {
            e.preventDefault();
            let btn = $(this);
            if (confirm("Ви впевнені, що хочете видалити цю заявку? ")) {
                $.ajax({
                    type: "POST",
                    url: '/application/delete_app_ajax/' + btn.parents('tr').attr('data-app-id'),
                    success: function (data) {
                        data = JSON.parse(data);
                        location.reload();
                    }
                });
            }
        });
    }

    //створення повторюваного селекту
    $('html').on('change', '#application_add #date_for', function () {
        console.log($(this).val());
        let date = $(this).val();
        // let date_ = new Date(2014, 0, 3);
        let date_ = new Date(date.substr(0, 4), date.substr(5, 2), date.substr(8, 2));
        console.log(date_);
        let select = '<select class="form-control" id="repeat_type" name="repeat_type" required="">' +
            '<option value=""></option>' +
            '<option value="day">Щодня</option>' +
            '<option value="week">Щотижня (' + getWeekDay(date_) + ')</option>' +
            '<option value="month">Щомісяця (' + date.substr(8, 2) + ' - го)</option>' +
            '</select>';
        $('#repeat_type').html(select);
    });

    $('html').on('change', '#application_add #repeat_type', function () {
        $('#repeat').prop("checked", true);
    });

    $('html').on('click', '.delete_uploaded_file', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей файл? ")) {
            let file = $(this).parent('span').find('a').attr("href");
            $.ajax({
                type: "POST",
                url: '/application/delete_uploaded_file_ajax/' + file,
                data: {
                    file: file,
                    test: 'test'
                },
                success: function (data) {
                    data = JSON.parse(data);
                    btn.parent('span').remove();
                }
            });
        }
    });

    $('html').on('click', '.add_director_comment', function (e) {
        e.preventDefault();
        let comment = $(this).next('.director_comment').find('span').text();
        let id = $(this).parents('tr').attr('data-app-id');
        console.log(id);
        console.log($(this).next('.director_comment').find('span'));
        console.log(comment);
        let html = '<div class="director_comment_popup" data-app_id="' + id + '"><textarea class="form-control">' + comment + '</textarea><input type="button" class="btn btn-info" id="save_director_comment" value="Зберегти" /></div>';
        open_small_popup(html, 300, 200)
    });


    $('html').on('click', '#save_director_comment', function (e) {
        e.preventDefault();
        let btn = $(this);
        let id = btn.parents('.director_comment_popup').attr('data-app_id');
        let comment = btn.parents('.director_comment_popup').find('textarea').val();
        $.ajax({
            type: "POST",
            url: '/application/save_comment_ajax/' + id,
            data: {
                comment: comment
            },
            success: function (data) {
                $('table tbody tr').each(function () {
                    if ($(this).attr('data-app-id') === id) {
                        $(this).find('.director_comment').html('<hr/>' +
                            '<strong>Коментар директора:</strong>' +
                            '<span>' + comment + '</span>');
                        close_popup();
                    }
                });
                // location.reload();
            }
        });
    });

    $('html').on('click', '#filter_by_date', function (e) {
        e.preventDefault();
        window.location = $(this).attr('data_url') + $('#date_from').val() + '/' + $('#date_to').val()
    });

    if ($("#search_app_id").length > 0 && $("#search_app_keywords").length > 0) {
        let displayed_search_results = [];
        $("#search_app_id").select2({
            placeholder: 'Номер',
            escapeMarkup: function (markup) {
                return markup;
            },
            "language": {
                "noResults": function () {
                    return "Заявки з таким номером не знайдено";
                }
            },
            ajax: {
                url: '/application/search_by_id_ajax',
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

        $("#search_app_keywords").select2({
            placeholder: 'Ключові слова',
            escapeMarkup: function (markup) {
                return markup;
            },
            "language": {
                "noResults": function () {
                    return "Заявки по таких ключових словах не знайдено";
                }
            },
            ajax: {
                url: '/application/search_ajax',
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

        $('#search_app_id, #search_app_keywords').on('select2:select', function (e) {
            e.preventDefault();
            let id = $(this).val();
            displayed_search_results.push(id);
            $.ajax({
                type: "POST",
                url: '/application/display_search_results_ajax',
                data: {
                    ids: displayed_search_results
                },
                success: function (data) {
                    data = JSON.parse(data);
                    $('#search_table_block').html(data);
                }
            });
        });

        $(document).on('click', '#search_result_table .close_app', function (e) {
            e.preventDefault();
            displayed_search_results.remove($(this).parents('tr').attr('data-app-id'));
            $(this).parents('tr').remove();
            console.log($('#search_result_table tbody tr').length);
            if ($('#search_result_table tbody tr').length === 0) {
                $('#search_result_table_block').remove();
            }
        });

        $(document).on('click', '#search_result_table .close_search_table', function (e) {
            e.preventDefault();
            $(this).parents('#search_result_table_block').remove();
        });
    }

    $(document).on('click', '.open_application_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('application_sidenav', 300);
        // fill_operation_sidenav();
    });
});

Array.prototype.remove = function (value) {
    var idx = this.indexOf(value);
    if (idx !== -1) {
        // Второй параметр - число элементов, которые необходимо удалить
        return this.splice(idx, 1);
    }
    return false;
}