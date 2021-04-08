jQuery(document).ready(function () {
    let $uiAccordion = $('.js-ui-accordion');
    if ($uiAccordion.length > 0) {
        $uiAccordion.accordion({
            collapsible: true,
            heightStyle: 'content',

            activate: function activate(event, ui) {
                let newHeaderId = ui.newHeader.attr('id');

                if (newHeaderId) {
                    history.pushState(null, null, '#' + newHeaderId);
                }
            },

            create: function create(event, ui) {
                let $this = $(event.target);
                let $activeAccordion = $(window.location.hash);

                if ($this.find($activeAccordion).length) {
                    $this.accordion('option', 'active', $this.find($this.accordion('option', 'header')).index($activeAccordion));
                }
            }
        });
    }


    $(document).on('click', '.add_operation_to_project_open', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('operation_sidenav', 300);
        $('#operation_sidenav [name=project_id]').val($(this).attr('data-project_id'));
        $('#operation_sidenav [name=contractor_id]').val($(this).attr('data-contractor_id'));
    });

    $(document).on('click', '#sales .edit_project', function (event) {
        event.preventDefault();
        let btn = $(this);
        openNav('project_sidenav', 300);
        $.ajax({
            type: "POST",
            url: 'project/get_ajax/' + btn.parents('tr').attr('data-project_id'),
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

    $(document).on('click', '.project_ops', function (event) {
        event.preventDefault();
        let id = $(this).attr('data-project_id');
        $.ajax({
            type: "POST",
            url: '/sales/operations_ajax/' + id,
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

    $('html').on('click', '.operations_types .btn', function (e) {
        e.preventDefault();
        $('#sales_project_operations .operation_panel').hide();
        $('.operations_types .btn').removeClass('btn-dark');
        $(this).addClass('btn-dark');
        let panel = $(this).attr('href').substr(1);
        console.log('#panel_' + panel);
        $('#panel_' + panel).show();
    });

    $('.product_form #department_id').change(function (e) {
        if ($(this).val() != "") {
            $('.product_form [name=article_id]').prop("disabled", false);
            $('.product_form span.article_id_warning').hide();
            if ($(".product_form [name=article_id]").length > 0) {
                $(".product_form [name=article_id]").select2({
                    placeholder: 'Стаття',
                    customClass: "form-control",
                    escapeMarkup: function (markup) {
                        return markup;
                    },
                    ajax: {
                        url: '/articles/search_ajax',
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
        } else {
            $('.product_form [name=article_id]').prop("disabled", true);
            $('.product_form span.article_id_warning').show();
            $(".product_form [name=article_id] option:selected").prop("selected", false)
        }
    });

    if ($(".product_form #question_group_ids").length > 0) {
        $(".product_form #question_group_ids").select2({
            placeholder: 'Групи питань',
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
                url: '/sales/get_question_groups_ajax',
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
    }

    if ($(".product_form [name=storage_name_id]").length > 0) {
        $(".product_form [name=storage_name_id]").select2({
            placeholder: 'Назва складу',
            customClass: "form-control",
            escapeMarkup: function (markup) {
                return markup;
            },
            ajax: {
                url: '/sales/get_account_storage_names_ajax',
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

    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $('html').on('click', '.btn_add_product', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');

        $.ajax({
            type: "post",
            url: '/sales/add_product_ajax',
            data: form.serializeObject(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
        return false;
    });

    $(document).on('click', '.open_product_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('product_sidenav', 300);
        // fill_operation_sidenav();
    });

    $(document).on('click', '.open_question_group_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('question_group_sidenav', 300);
        // fill_operation_sidenav();
    });

    $(document).on('click', '.open_client_sidenav', function (event) {
        event.preventDefault();
        openNav('client_sidenav', 300);
    });
});


