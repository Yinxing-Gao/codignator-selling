jQuery(document).ready(function () {
//credit_add
    $(document).on('click', '.btn_send_app', function (event) {
        event.preventDefault();
        let errors = [];
        if ($('#credit_add form [name=department_id]').val() === '') {
            errors.push('Виберіть, будь ласка, департамент. Це потрібно, щоб правильно розподіляти розходи між різними структурами')
        }

        if ($('#credit_add form [name=contractor_id]').val() === '') {
            errors.push('Виберіть або введіть, будь ласка, контрагента.')
        }

        if ($('#credit_add form [name=amount]').val() <= 0) {
            errors.push('Введіть, будь ласка, суму.')
        }

        if ($('#credit_add form [name=end_date]').val().length <= 6) {
            errors.push('Введіть, будь ласка, дату на коли потрібно повернути гроші')
        }

        let form = $(this).closest('form');
        let action = $(this).attr('data-action');
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/credit/' + action + '_ajax',
                data: form.serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    console.log(data.id);
                    if (data.status === 'ok') {
                        if (action === 'add') {
                            let success = '<h3 class="success">Кредит успішно створено</h3> <span class="normal">Зараз вас перенаправить на список кредитів</span>';
                            open_popup(success);
                        } else if (action === 'edit') {
                            let success = '<h3 class="success">Кредит успішно відредаговано</h3> <span class="normal">Зараз вас перенаправить на список кредитів</span>';
                            open_popup(success);
                        }

                        setTimeout(function () {
                            window.location = '/credit';
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

    if ($("#credit_add form [name=contractor_id]").length > 0) {
        $("#credit_add form [name=contractor_id]").select2({
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
    }

    // $(document).on('click', '#add_new_contractor', function (event) {
    //     let name = $('.select2-search__field')[0].value;
    //     event.preventDefault();
    //     $.ajax({
    //         type: "POST",
    //         url: '/contractor/add_ajax',
    //         dataType: "json",
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

    $('html').on('click', '.delete_credit', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей кредит? ")) {
            $.ajax({
                type: "POST",
                url: '/credit/delete_ajax/' + btn.parents('tr').attr('data-credit_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });
});