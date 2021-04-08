jQuery(document).ready(function () {

//application_add
//     jQuery(document).on('click', '.btn_add_project', function (event) {
//         event.preventDefault();
//         let errors = [];
//         if ($('#department').val() === '') {
//             errors.push('Виберіть, будь ласка, департамент. Це потрібно, щоб правильно розподіляти розходи між різними структурами')
//         }
//         console.log($('#project_sidenav #amount').val());
//         if ($('#project_sidenav #amount').val() <= 0) {
//             errors.push('Введіть, будь ласка, суму.')
//         }
//
//         if ($('#project_sidenav #start_date').val().length <= 6) {
//             errors.push('Введіть, будь ласка, дату старту проекту')
//         }
//
//         if ($('#project_sidenav #end_date').val().length <= 6) {
//             errors.push('Введіть, будь ласка, орієнтовну дату завершення проекту')
//         }
//         let form = $(this).closest('form');
//
//
//         console.log(errors);
//         if (errors.length === 0) {
//             $.ajax({
//                 type: "POST",
//                 url: '/project/add_edit_ajax',
//                 data: form.serialize(),
//                 success: function (data) {
//                     data = JSON.parse(data);
//                     console.log(data.id);
//                     if (data.status === 'ok') {
//
//                         let success = '<h3 class="success">Проект успішно створено</h3></span> <span class="normal">Зараз вас перенаправить на список проектів</span>';
//                         if (data.message === 'updated') {
//                             success = '<h3 class="success">Проект успішно оновлено</h3></span> <span class="normal">Зараз вас перенаправить на список проектів</span>';
//                         }
//                         closeNav();
//                         open_popup(success);
//                         setTimeout(function () {
//                             close_popup();
//                         }, 3000);
//                     } else {
//                         alert(data.message);
//                     }
//                 }
//             });
//         } else {
//             let error_html = '';
//             $.each(errors, function (index, value) {
//                 error_html += '<span class="error">' + value + '</span>';
//             });
//             open_popup(error_html)
//
//         }
//     });

    $(document).on('click', '#projects  .combine', function (e) {
        e.preventDefault();
        var ids_str = '';
        $('.project_checkbox').each(function (index) {
            if ($(this).is(':checked')) {
                ids_str += $(this).parents('tr').attr('data-project_id') + ',';
            }
        });
        ids_str = ids_str.substring(0, ids_str.length - 1);
        console.log(ids_str);
        window.location = '/project/combine/' + ids_str;
    });

    $(document).on('click','#projects .edit_project', function (event) {
        event.preventDefault();
        let btn = $(this);
        openNav('project_sidenav', 300);
        $.ajax({
            type: "POST",
            url: 'project/get_ajax/' + btn.parents('tr').attr('data-project_id'),
            // data: {
            //     project_id: btn.attr('data-project_id')
            // },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    fill_project_sidenav(data.result);
                } else {
                    alert(data.message);
                }
            }
        });
    });

    // if ($("#contract_id").length > 0) {
    //     $("#contract_id").select2({
    //         placeholder: 'Договір',
    //         // minimumInputLength: 2,
    //         customClass: "form-control",
    //         language: {
    //             inputTooShort: function () {
    //                 return 'Введіть мінімум 2 символи';
    //             },
    //             noResults: function () {
    //                 return '<button class="form-control" id="add_new_contract">Додати договір</a>';
    //             },
    //         },
    //         escapeMarkup: function (markup) {
    //             return markup;
    //         },
    //         ajax: {
    //             url: '/contract/get_ajax',
    //             dataType: "json",
    //             type: 'POST',
    //             processResults: function (data) {
    //                 return {
    //                     results: $.map(data, function (item) {
    //                         return {
    //                             text: item.number,
    //                             id: item.id
    //                         }
    //                     })
    //                 };
    //             }
    //         }
    //     });
    // }

    // if ($("#products").length > 0) {
    //     $("#products").select2({
    //         placeholder: 'Продукти',
    //         // minimumInputLength: 2,
    //         customClass: "form-control",
    //         language: {
    //             inputTooShort: function () {
    //                 return 'Введіть мінімум 2 символи';
    //             },
    //             // noResults: function () {
    //             //     return '<button class="form-control" id="add_new_contract">Додати договір</a>';
    //             // },
    //         },
    //         escapeMarkup: function (markup) {
    //             return markup;
    //         },
    //         ajax: {
    //             url: '/sales/get_products_ajax',
    //             dataType: "json",
    //             type: 'POST',
    //             processResults: function (data) {
    //                 return {
    //                     results: $.map(data, function (item) {
    //                         return {
    //                             text: item.name,
    //                             id: item.id
    //                         }
    //                     })
    //                 };
    //             }
    //         }
    //     });
    // }

    // $(document).on('click', '#add_new_contract', function (event) {
    //
    //     let number = $('.select2-search__field')[0].value;
    //     event.preventDefault();
    //     $.ajax({
    //         type: "POST",
    //         url: '/contract/add_ajax',
    //         data: {
    //             number: number
    //         },
    //         success: function (data) {
    //             data = JSON.parse(data);
    //             if (data.status === 'ok') {
    //                 let newOption = new Option(number, data.id, false, true);
    //                 $("#project_sidenav #contract_id").append(newOption).trigger('change');
    //                 $("#project_sidenav #contract_id").select2('close');
    //             } else {
    //                 alert(data.message);
    //             }
    //         }
    //     });
    // });

    $('html').on('click', '.delete_project', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей проект? Всі операції, начислення, заявки і план факти по цьому проект будуть видалені ")) {
            $.ajax({
                type: "POST",
                url: '/project/delete_ajax/' + btn.parents('tr').attr('data-project_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $(document).on('click', '.open_specification', function (event) {
        event.preventDefault();
        let project_id = $(this).parents('tr').attr('data-project_id');
        $.ajax({
            type: "POST",
            url: '/production/open_specification_ajax/' + project_id,
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);

                if (data.status === 'ok') {
                    window.location = data.link;
                } else {
                    alert(data.message);
                }
            }
        });
    });
});

// function fill_project_sidenav(data = null) {
//     if (data !== null) {
//         $('#project_sidenav #title').text('Редагувати проект');
//         $('#project_sidenav [name=project_id]').val(data.id);
//         $('#project_sidenav #department').val(data.department_id);
//         $('#project_sidenav #author').val(data.date);
//         $('#project_sidenav [name=name]').val(data.name);
//         $('#project_sidenav #amount').val(data.contract_amount);
//         $('#project_sidenav #currency').val(data.contract_currency);
//         $('#project_sidenav #type_id').val(data.contract_type_id);
//         $('#project_sidenav #contract_id').val(data.contract_id).change();
//         $('#project_sidenav #products').val(data.products).change();
//         $('#project_sidenav #status').val(data.status);
//         $('#project_sidenav #comment').val(data.comment);
//     } else {
//         $('#project_sidenav #title').text('Додати проект');
//         $('#project_sidenav #department').val();
//         $('#project_sidenav #author').val();
//         $('#project_sidenav [name=name]').val();
//         $('#project_sidenav #amount').val();
//         $('#project_sidenav #currency').val();
//         $('#project_sidenav #type_id').val();
//         $('#project_sidenav #contract_id').val().change();
//         $('#project_sidenav #products').val().change();
//         $('#project_sidenav #status').val('new');
//         $('#project_sidenav #comment').val();
//     }
// }