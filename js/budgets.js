$(document).ready(function () {

    $(document).on('click', '.open_budgets_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('budgets_sidenav', 300);
        // fill_operation_sidenav();
    });

    $('html').on('click', '.btn_add_budget', function (e) {
        e.preventDefault();
        let btn = $(this);
        let form = btn.parents('form');

        $.ajax({
            type: "POST",
            method: "POST",
            url: '/finances/add_budget_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    if ($("#budgets_sidenav [name=article_id]").length > 0) {
        $("#budgets_sidenav [name=article_id]").select2({
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
});
