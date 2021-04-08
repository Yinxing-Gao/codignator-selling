$(document).ready(function () {
    $('#contract_list table .products, #products').select2({
        placeholder: 'Продукти',
        customClass: "form-control",
        escapeMarkup: function (markup) {
            return markup;
        },
        ajax: {
            url: '/contract/get_products_ajax',
            dataType: "json",
            type: 'POST',
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            options: item.options,
                            id: item.id,
                        }
                    })
                };
            },
        }
    });

    $(document).on('click', '.btn_add_contract', function (event) {
        event.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/contract/add_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '.delete_contract', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей договір? ")) {
            $.ajax({
                type: "POST",
                url: '/contract/delete_ajax/' + btn.parents('tr').attr('data-contract_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });
});