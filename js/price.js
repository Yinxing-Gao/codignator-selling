$(document).ready(function () {
    $('html').on('change', '#prices .price, #prices .currency', function (e) {
        let group = $(this).parents('.price_group');
        $.ajax({
            type: "POST",
            url: '/price/change_price_ajax',
            data: {
                product_id: group.attr('data-product_id'),
                type: group.attr('data-type'),
                amount: group.find('.price').val(),
                currency: group.find('.currency').val()
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    notification('Зміни успішно збережено');
                }
            }
        });
    });
});