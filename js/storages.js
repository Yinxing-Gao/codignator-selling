jQuery(document).ready(function () {
    var select = '';
    $('html').on('click', '.btn_add_one_storage_name', function (e) {
        e.preventDefault();
        var storage_id = $('[name=storage_id]').val();
        var errors = [];
        if ($('#item').val().length <= 4) {
            errors.push('Введіть, будь ласка, корректну назву')
        }
        if ($('#units').val() === '') {
            errors.push('Виберіть, будь ласка, одиницю вимірювання')
        }
        var form = $(this).closest('form');
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/storage/add_item_ajax/' + storage_id,
                data: form.serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        window.location = '/storage/names/' + storage_id;
                    } else {
                        alert(data.message);
                    }
                }
            });
        } else {
            var error_html = '';
            $.each(errors, function (index, value) {
                error_html += '<span class="error">' + value + '</span>';
            });
            open_popup(error_html)

        }
    });

    $('html').on('click', '.btn_add_plural_storage_names', function (e) {
        e.preventDefault();
        var storage_id = $('[name=storage_id]').val();
        var errors = [];

        if ($('#data').val().length < 3) {
            errors.push('Введіть, будь ласка, коректний список позицій')
        }
        var form = $(this).closest('form');
        if (errors.length === 0) {
            $.ajax({
                type: "POST",
                url: '/storage/add_item_ajax/' + storage_id,
                data: form.serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        window.location = '/storage/names/' + storage_id;
                    } else {
                        alert(data.message);
                    }
                }
            });
        } else {
            var error_html = '';
            $.each(errors, function (index, value) {
                error_html += '<span class="error">' + value + '</span>';
            });
            open_popup(error_html)

        }
    });

    $('html').on('click', '.delete_storage_name', function (e) {
        e.preventDefault();
        var btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цю позицію зі складу? ")) {
            $.ajax({
                type: "POST",
                url: '/storage/delete_name_ajax/' + $(this).parents('tr').attr('data-name_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $('html').on('click', '.add_position', function (e) {
        e.preventDefault();
        if (select.length == 0) {
            $.ajax({
                type: "POST",
                url: '/storage/get_units_ajax',
                success: function (data) {
                    data = JSON.parse(data);
                    make_select(data);
                }
            });
        } else {
            make_tr(select);
        }
    });

    $('html').on('click', '.do_inventory', function (e) {
        e.preventDefault();
        var data = {
            items: [],
            new_items: []
        };
        var storage_id = $('[name=storage_id]').val();
        $('table tr').each(function (index) {
            var tr = $(this);
            if (index !== 0) {
                var id = tr.attr('data-item_id');
                if (parseInt(id) !== 0) {
                    data.items.push(
                        {
                            id: id,
                            price: tr.find('[name=buy_price]').val(),
                            amount: tr.find('[name=amount]').val(),
                            description: tr.find('[name=description]').val()
                        }
                    )
                } else {
                    data.new_items.push(
                        {
                            name: tr.find('[name=name]').val(),
                            unit_id: tr.find('[name=unit_id]').val(),
                            price: tr.find('[name=buy_price]').val(),
                            amount: tr.find('[name=amount]').val(),
                            description: tr.find('[name=description]').val()
                        }
                    )
                }
            }
        });
        console.log(data);
        $.ajax({
            type: "POST",
            url: '/storage/do_inventory_ajax/' + storage_id,
            data: data,
            success: function (data) {
                data = JSON.parse(data);
                window.location = '/storage/storage/' + storage_id;
            }
        });
    });

    $('html').on('click', '.edit_storage_name', function (e) {
        e.preventDefault();
        var btn = $(this);

        $('.name_td_input').hide();
        $('.name_td_span').show();
        $('.unit_td_input').hide();
        $('.unit_td_span').show();
        $('.price_td_input').hide();
        $('.price_td_span').show();
        $('.amount_td_input').hide();
        $('.amount_td_span').show();
        $('.description_td_input').hide();
        $('.description_td_span').show();

        $('.done_editing_storage_name').hide();
        $('.edit_storage_name').show();

        var name_td = btn.parents('tr').find('.storage_name_td');
        var unit_td = btn.parents('tr').find('.storage_unit_td');
        var price_td = btn.parents('tr').find('.storage_price_td');
        var amount_td = btn.parents('tr').find('.storage_amount_td');
        var description_td = btn.parents('tr').find('.storage_description_td');

        var name_input = '<input class="name_td_input form-control" style="width:100%" type="text" value="' + name_td.find('span').html() + '" /><span class="name_td_span" style="display:none">' + name_td.find('span').html() + '</span>';
        $.ajax({
            type: "POST",
            url: '/storage/get_units_ajax',
            success: function (data) {
                data = JSON.parse(data);
                make_select2(data, unit_td.attr('data-unit_id'), unit_td);
            }
        });
        var price_input = '<input class="price_td_input form-control" style="width:100%" type="number" value="' + price_td.find('span').html() + '" /><span class="price_td_span" style="display:none">' + price_td.find('span').html() + '</span>';
        var amount_input = '<input class="amount_td_input form-control" style="width:60px" type="number" value="' + amount_td.find('span').html() + '" /><span class="amount_td_span" style="display:none">' + amount_td.find('span').html() + '</span>';
        var description_input = '<textarea class="description_td_input form-control" style="width:100%; height: 85px;" >' + description_td.find('span').html() + '</textarea><span class="description_td_span" style="display:none">' + description_td.find('span').html() + '</span>';

        name_td.html(name_input);
        // unit_td.html(unit_input);
        price_td.html(price_input);
        amount_td.html(amount_input);
        description_td.html(description_input);

        btn.hide();
        btn.next('.done_editing_storage_name').show();
    });

    $('html').on('click', '.done_editing_storage_name', function (e) {
        e.preventDefault();
        var btn = $(this);

        var name = btn.parents('tr').find('.name_td_input').val();
        var unit_id = btn.parents('tr').find('.unit_td_select').val();
        var price = btn.parents('tr').find('.price_td_input').val();
        var amount = btn.parents('tr').find('.amount_td_input').val();
        var description = btn.parents('tr').find('.description_td_input').val();
        $.ajax({
            type: "POST",
            url: '/storage/change_storage_names_ajax/' + $(this).parents('tr').attr('data-name_id'),
            data: {
                name: name,
                unit_id: unit_id,
                price: price,
                amount: amount,
                description: description
            },
            success: function (data) {
                data = JSON.parse(data);
                btn.hide();
                btn.prev('.edit_storage_name').show();
                btn.parents('tr').find('.storage_name_td').find('span').html(name);
                btn.parents('tr').find('.storage_unit_td').find('span').html(btn.parents('tr').find('.unit_td_select').children("option:selected").text());
                btn.parents('tr').find('.storage_price_td').find('span').html(price);
                btn.parents('tr').find('.storage_amount_td').find('span').html(amount);
                btn.parents('tr').find('.storage_description_td').find('span').html(description);

                $('.name_td_input').hide();
                $('.unit_td_select').hide();
                $('.price_td_input').hide();
                $('.amount_td_input').hide();
                $('.description_td_input').hide();

                $('.name_td_span').show();
                $('.unit_td_span').show();
                $('.price_td_span').show();
                $('.amount_td_span').show();
                $('.description_td_span').show();
            }
        });
    });

    async function make_select(array) {
        select = '<select style="width:100px" class="form-control" name="unit_id" required="">';
        for (const item of array) {
            await item;
            select += '<option value="' + item.id + '">' + item.name + '</option>';
        }
        select += '</select>';
        make_tr(select);
    }

    async function make_select2(array, item_id, unit_td) {
        select = '<select style="width:80px" class="form-control unit_td_select" name="unit_id" required="">';
        for (const item of array) {
            await item;
            console.log(parseInt(item_id));
            console.log(parseInt(item.id));
            if (parseInt(item_id) === parseInt(item.id)) {


                select += '<option selected="selected" value="' + item.id + '">' + item.name + '</option>';
            } else {
                select += '<option value="' + item.id + '">' + item.name + '</option>';
            }
        }
        select += '</select>';
        select += '<span class="unit_td_span" style="display:none">' + unit_td.find('span').html() + '</span>';
        unit_td.html(select);
    }

    function make_tr(select) {
        $('table').append('<tr data-item_id="0">' +
            '<td>#</td>' +
            '<td><input type="text" class="form-control" style="width:100%" name="name" class="new_item_name" placeholder="Введіть ім\'я"></td>' +
            '<td>' + select + '</td>' +
            '<td><input class="form-control" type="text" name="buy_price" placeholder="Введіть ціну" /></td>' +
            '<td><input class="form-control" type="number" name="amount" placeholder="Введіть к-сть" /></td>' +
            '<td><textarea class="form-control" name="description" placeholder="Введіть опис"></textarea></td>' +
            '</tr>');
    }
    // for open storage item edit nav sidebar
    $(document).on('click', '.open_storage_add_item_sidenav', function (event) {
        event.preventDefault();
        openNav('storage_add_item_sidenav', 300);
    });

    // for open storage item edit nav sidebar
    $(document).on('click', '.btn_add_storage_item', function (event) {
        event.preventDefault();
        // alert("stop here");
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/storage/add_name_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    // for open storage item edit nav sidebar
    $(document).on('click', '.open_edit_storage_item_name_sidenav', function (event) {
        event.preventDefault();

        // var id = tr.attr('data-item_id');
        // alert (id);
        openNav('storage_edit_sidenav', 300);
    });
});