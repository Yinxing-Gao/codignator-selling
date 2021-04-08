$(document).ready(function () {

    $('html').on('click', '.show_all_comments', function (e) {
        e.preventDefault();
        let btn = $(this);
        let block = btn.parents('td');
        let shown = btn.attr('data-shown') === '1';

        if (shown) {
            block.find('.comment').each(function (index) {
                if (index < (parseInt(block.find('.comment').length) - 1)) {
                    $(this).addClass('hidden');
                }
                console.log(index);
                console.log(block.find('.comment').length);
            });
            block.find('.comment:last-child').removeClass('hidden');
            btn.attr('data-shown', 0);
            btn.text('Показати всі');
        } else {
            block.find('.comment').each(function (index) {
                $(this).removeClass('hidden');
            });
            btn.attr('data-shown', 1);
            btn.text('Згорнути');
        }

    });

    $('html').on('click', '.add_lead_comment', function (e) {
        e.preventDefault();
        let lead_id = $(this).parents('td').attr('data-lead_id');
        let html = '<div class="lead_comment_block">' +
            '<p>Введіть коментар по даному клієнту</p>' +
            '<textarea class="form-control"></textarea>' +
            '<input type="btn" data-lead_id="' + lead_id + '" class="form-control btn btn-info add_lead_comment_btn" value="Відправити" /> ' +
            '</div>';
        open_small_popup(html, 350, 215)
    });

    $('html').on('click', '.add_lead_comment_btn', function (e) {
        e.preventDefault();
        let btn = $(this);
        let lead_id = btn.attr('data-lead_id');
        let comment = btn.parents('.lead_comment_block').find('textarea').val();
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/marketing/add_lead_comment_ajax/' + lead_id,
            data: {
                comment: comment
            },
            success: function (data) {
                data = JSON.parse(data);
                $('.comment_td').each(function (index) {
                    if (parseInt($(this).attr('data-lead_id')) === parseInt(lead_id)) {
                        $(this).find('.comment').addClass('hidden');
                        $(this).prepend('<div class="comment">\n' +
                            '<b>' + data.date + '</b>\n' +
                            comment +
                            '</div>');
                    }
                });
                close_popup();
            }
        });
    });

    $('html').on('click', '.add_lead_task', function (e) {
        e.preventDefault();
        let lead_id = $(this).parents('td').attr('data-lead_id');
        let html = '<div class="lead_task_block">' +
            '<form>' +
            '<p>Введіть задачу по даному клієнту</p>' +
            '<textarea class="form-control task_name" placeholder="назва*" name="task"></textarea>' +
            '<textarea class="form-control task_description" placeholder="детальний опис" name="description"></textarea>' +
            '<input type="number" class="form-control task_statistics" placeholder="статистика" name="statistic" />' +
            '<div class="row">' +
            '<div class="col-md-6"><input type="date" class="form-control task_date_to" name="date_to"></div>' +
            '<div class="notify_div col-md-6"><input type="checkbox" class="form-control the_task_notify" name="notify" id="the_task_notify"><label for="the_task_notify">Сповістити</label></div>' +
            '</div>' +
            '<input type="submit" data-lead_id="' + lead_id + '" class="form-control btn btn-info add_lead_task_btn" value="Відправити" /> ' +
            '</form></div>';
        open_small_popup(html, 350, 340)
    });

    $('html').on('click', '.add_lead_task_btn', function (e) {
        e.preventDefault();
        let btn = $(this);
        let lead_id = btn.attr('data-lead_id');
        let comment = btn.parents('.lead_task_block').find('textarea').val();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/marketing/add_lead_task_ajax/' + lead_id,
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                $('.task_td').each(function (index) {
                    if (parseInt($(this).attr('data-lead_id')) === parseInt(lead_id)) {
                        $(this).find('.comment').addClass('hidden');
                        $(this).prepend('<div class="comment">\n' +
                            '<b>' + data.date + '</b>\n' +
                            comment +
                            '</div>');
                    }
                });
                close_popup();
            }
        });
    });

    $(document).on('click', '.edit_lead', function (event) {
        event.preventDefault();
        let lead_id = $(this).parents('td').attr('data-lead_id');
        $.ajax({
            type: "POST",
            url: '/marketing/lead_info_ajax/' + lead_id,
            success: function (data) {
                data = JSON.parse(data);
                // console.log(data);
                open_popup(data);

                $('#lead_info .all_question_groups input').click(function () {
                    console.log("checked");
                    let group_id = $(this).attr("data-question_group_id");
                    if (this.checked) {
                        $.ajax({
                            type: "POST",
                            method: "POST",
                            url: '/marketing/get_question_group_ajax/' + group_id,
                            data: {lead_id: lead_id},
                            success: function (response) {
                                response = JSON.parse(response);
                                $('#lead_info .answers_column .question_group_questions:first').before(
                                    '<div class="question_group_questions" data-question_group_id="' + response.id + '">' +
                                    '<h4>' + response.name + '</h4>' +
                                    '<div class="group_questions_content">' +
                                    '</div>' +
                                    '</div>'
                                );
                                $.each(response.questions, function (index, value) {
                                    console.log(value);
                                    $('#lead_info .answers_column .question_group_questions[data-question_group_id=' + value.group_id + '] .group_questions_content').append(
                                        '<div class="lead_answers">\n' +
                                        '<label>' + value.question + '</label>' +
                                        '<textarea class="form-control" data-question_id="' + value.id + '"' +
                                        'data-question_group_id="' + value.group_id + '">' +
                                        value.answer +
                                        '</textarea>\n' +
                                        '</div>'
                                    );
                                });
                            }
                        });
                    } else {
                        $('#lead_info .answers_column .question_group_questions[data-question_group_id=' + group_id + ']').remove();
                    }
                });
            }
        });
    });

    $(document).on('click', '.contacts_block .contact', function (event) {
        event.preventDefault();
        let type = $(this).attr('data-type');
        let label = "Інший контакт";
        switch (type) {
            case 'phone':
                label = "Телефон";
                break;
            case 'telegram':
                label = "Тelegram";
                break;
            case 'fb':
                label = "Ссилка на фб";
                break;
            default:
                break;
        }

        $('.contact_fields_block').append('<div class="contact_field"><label>' + label + '</label><input data-type="' + type + '" class="form-control" /></div>')
    });

    $('input[type=file]').on('change', function (event) {
        console.log('worked');
        files = this.files;

        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

        upload_files(files, '/marketing/upload_order_ajax');
    });

    $('html').on('click', '.btn_add_lead', function (e) {
        e.preventDefault();
        let btn = $(this);
        let form = btn.parents('form');
        let contacts = [];
        form.find('.contact_fields_block > .contact_field').each(function (index) {
            let input = $(this).find('input');
            contacts.push({
                type: input.attr('data-type'),
                value: input.val()
            });
        });

        console.log(JSON.stringify(form.find('[name=uploaded_files]').val()));
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/marketing/add_lead_ajax',
            data: {
                name: form.find('[name=name]').val(),
                product_id: form.find('#lead_product').val(),
                amount: form.find('[name=amount]').val(),
                currency: form.find('[name=currency]').val(),
                source_id: form.find('[name=source_id]').val(),
                qualification: form.find('[name=qualification]').val(),
                documents: form.find('[name=uploaded_files]').val(),
                contacts: contacts
            },
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
                // $('.comment_td').each(function (index) {
                //     if (parseInt($(this).attr('data-lead_id')) === parseInt(lead_id)) {
                //         $(this).find('.comment').addClass('hidden');
                //         $(this).prepend('<div class="comment">\n' +
                //             '<b>' + data.date + '</b>\n' +
                //             comment +
                //             '</div>');
                //     }
                // });
                // close_popup();
            }
        });
    });

    $(document).on('click', '.lead_operations', function (event) {
        event.preventDefault();
        let id = $(this).parents('td').attr('data-lead_id');
        $.ajax({
            type: "POST",
            url: '/marketing/operations_ajax/' + id,
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                open_popup(data);
                // if (data.status === 'ok') {
                //     window.location = '/';
                // } else {
                //     alert(data.message);
                // }
            }
        });
    });

    if ($("#lead_product").length > 0) {
        $("#lead_product").select2({
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

    if ($("#lead_source").length > 0) {
        let api_key = $('#api_key').val();
        $("#lead_source").select2({
            placeholder: 'Джерело',
            // minimumInputLength: 2,
            customClass: "form-control",
            language: {
                inputTooShort: function () {
                    return 'Введіть мінімум 2 символи';
                },
                noResults: function () {
                    return '<button class="form-control add_source_from_select2">Додати джерело</a>';
                },
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/marketing/get_sources_ajax/' + api_key,
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

    $(document).on('change', '.status', function (event) {
        event.preventDefault();
        let select = $(this);
        let status = $(this).val();
        let old_status = $(this).attr('data-old-value');
        let data = {status: status};
        // let row = $(this).closest('tr');
        let lead_id = $(this).closest('tr').attr('data-lead_id');

        switch (status) {
            case 'new':
            case 'presentation':
            case 'calculation':
            case 'contract': {
                if (
                    (old_status === "new" || old_status === "presentation")
                    && (status === "calculation" || status === "contract")
                ) {
                    if (window.confirm('Створюємо проект для прорахунку?')) {
                        data.create_project = true;
                    }
                }
            }

            case 'bad lead':
                console.log(status);
                $.ajax({
                    type: "POST",
                    url: '/marketing/edit_lead_ajax/' + lead_id,
                    data: data,
                    success: function (response) {
                        response = JSON.parse(response);
                        /*if(response.lead.status == "ok"
                            && (((status === "new" || status === "presentation") && (old_status === "calculation" || old_status === "contract"))
                            || ((old_status === "new" || old_status === "presentation") && (status === "calculation" || status === "contract")))
                        ){
                            // $(row).remove();
                        }*/
                        if (response.lead.status === 'ok') {
                            select.css("border", "2px solid lightgreen");
                            setTimeout(function () {
                                select.css("border", "");
                            }, 3000);
                        } else {
                            select.css("border", "2px solid red");
                            setTimeout(function () {
                                select.css("border", "");
                            }, 3000);
                        }
                    }
                });

        }
    });

    $(document).on('change', '.qualification', function (event) {
        event.preventDefault();
        let select = $(this);
        let qualification = $(this).val();
        let lead_id = $(this).closest('tr').attr('data-lead_id');

        $.ajax({
            type: "POST",
            url: '/marketing/edit_lead_ajax/' + lead_id,
            data: {
                qualification: qualification
            },
            success: function (response) {
                response = JSON.parse(response);
                if (response.lead.status === 'ok') {
                    select.css("border", "2px solid lightgreen");
                    setTimeout(function () {
                        select.css("border", "");
                    }, 3000);
                } else {
                    select.css("border", "2px solid red");
                    setTimeout(function () {
                        select.css("border", "");
                    }, 3000);
                }
            }
        });
    });

    $('html').on('click', '.save_lead_info', function (e) {
        e.preventDefault();
        let btn = $(this);
        let lead_id = btn.attr('data-lead_id');
        let form = btn.parents('form');
        let contacts = [];
        form.find('#lead_info .contact_fields_block > .contact_field').each(function (index) {
            let input = $(this).find('input');
            contacts.push({
                type: input.attr('data-type'),
                value: input.val()
            });
        });

        let answers = [];
        $('#lead_info .answers_column .lead_answers').each(function (index) {
            let input = $(this).find('textarea');
            answers.push({
                question_id: input.attr('data-question_id'),
                answer: input.val()
            });
        });

        let question_groups_ids = [];
        $('#lead_info .all_question_groups input:checked').each(function (index) {
            let group_id = $(this).attr("data-question_group_id");
            question_groups_ids.push(group_id);
        });
        question_groups_ids = question_groups_ids.join(",");
        console.log(JSON.stringify(form.find('[name=uploaded_files]').val()));
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/marketing/edit_lead_ajax/' + lead_id,
            data: {
                name: form.find('[name=name]').val(),
                product_id: form.find('#lead_product').val(),
                amount: form.find('[name=amount]').val(),
                currency: form.find('[name=currency]').val(),
                source_id: form.find('[name=source_id]').val(),
                qualification: form.find('[name=qualification]').val(),
                docs: form.find('[name=uploaded_files]').val(),
                question_groups_ids: question_groups_ids,
                contacts: contacts,
                answers: answers
            },
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });


    $(document).on('click', '.open_lead_sidenav', function (event) {
        event.preventDefault();
        openNav('lead_sidenav', 300);
    });



    $(document).on('click', '.edit_task', function (event) {
        event.preventDefault();
        let task_id = $(this).attr('data-task_id');
        let form = $("#edit_lead_task_sidenav").find("#edit_task_form");
        $.ajax({
            type: "POST",
            url: '/marketing/get_lead_task_ajax/' + task_id,
            success: function (response) {
                response = JSON.parse(response);
                $(form).find("[name=id]").val(response.id);
                $(form).find("[name=task]").val(response.task);
                $(form).find("[name=comment]").val(response.comment);
                $(form).find("[name=statistics]").val(response.statistics);

                $(form).find("[name=user_id]").val(response.user_id);
                $(form).find("[name=user_id]").trigger("change");

                let date_to = new Date(response.date_to * 1000);
                $(form).find("[name=date_to]").val(date_to.getFullYear() + '-' + (date_to.getMonth() + 1).toString().padStart(2, '0') + '-' + date_to.getDate().toString().padStart(2, '0'));

                if (response.notify == 1) {
                    $(form).find("[name=notify]").prop('checked', true);
                }

                openNav('edit_lead_task_sidenav', 300);
            }
        });
    });

    $('html').on('click', '.edit_task_btn', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "post",
            url: '/marketing/edit_lead_task_ajax',
            data: form.serialize(),
            success: function (response) {
                response = JSON.parse(response);
                let task_card = $("td.task_td").find(".task[data-task_id=" + response.id + "]");
                $(task_card).find(".task_card_name").html($(form).find("[name=task]").val());
                $(task_card).find(".task_card_date").html($(form).find("[name=date_to]").val());
                closeNav();
            }
        });
        return false;
    });

    $('html').on('click', '.finish_task', function (e) {
        e.preventDefault();
        let task_id = $(this).attr('data-task_id');
        $.ajax({
            type: "post",
            url: '/marketing/edit_lead_task_status_ajax',
            data: {
                id: task_id,
                status: 'done'
            },
            success: function (response) {
                response = JSON.parse(response);
                let task_card = $("td.task_td").find(".task[data-task_id=" + response.id + "]");
                if (response.status === 'ok') {
                    task_card.css("border", "2px solid lightgreen");
                    setTimeout(function () {
                        task_card.remove();
                    }, 3000);
                } else {
                    task_card.css("border", "2px solid red");
                    setTimeout(function () {
                        task_card.css("border", "");
                    }, 3000);
                }
            }
        });
        return false;
    });

});