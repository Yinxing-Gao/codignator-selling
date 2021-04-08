jQuery(document).ready(function () {
    $(document).on('click', '[name=wallet_type]', function (e) {
        let cash_or_cart = $(this).val();
        if (cash_or_cart === 'card') {
            $('.banks').show();
            $('#banks').trigger('change');
            $('.actual_balance').hide();
        } else if (cash_or_cart === 'cash') {
            $('.actual_balance').show();
            $('.banks').hide();
            $('.monobank').hide();
            // $('.monobank_info').hide();
            // $('.monobank_cards').hide();
            // $('.monobank_token').hide();
            $('.privat_bank').hide();
            // $('.privat_bank_info').hide();
        }
    });

    $(document).on('change', '#banks', function (e) {
        let bank = $(this).val();
        $('.actual_balance #actual_balance').val();
        $('.actual_balance #card_currency').val('');
        $('.actual_balance [name=merchant_id]').val('');
        if (bank === "2") {
            $('.privat_bank').hide();
            $('.monobank').show();
        }
        if (bank === "1") {
            $('.monobank').hide();
            $('.privat_bank').show();
        }
    });

    $(document).on('keyup', '.monobank_token', function (e) {
        let token = $(this).val();
        console.log(token.length);
        if (token.length >= 44 && token.length <= 46) {
            162034
            $.ajax({
                type: "POST",
                url: '/wallet/get_monobank_wallets_ajax',
                data: {
                    token: token
                },
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        let accounts_table = "<h3>Виберіть потрібну карту:</h3>" +
                            "<table class=\"table table100 ver1\" border=\"1px solid\">\n" +
                            "\t\t\t<thead class=\"thead-dark\">\n" +
                            "\t\t\t<tr>\n" +
                            "\t\t\t\t<th></th>\n" +
                            "\t\t\t\t<th>ID</th>\n" +
                            "\t\t\t\t<th>Баланс</th>\n" +
                            "\t\t\t\t<th>Валюта</th>\n" +
                            "\t\t\t\t<th>Кредитний ліміт</th>\n" +
                            "\t\t\t</tr>\n" +
                            "\t\t\t</thead>\n" +
                            "\t\t\t<tbody>";
                        $.each(data.accounts, function (index, account) {

                            accounts_table += '<tr>' +
                                '<td><input type="radio" name="mono_card" class="form-control"/> </td>' +
                                '<td class="id">' + account.card_id + '</td>' +
                                '<td class="balance">' + account.card_balance + '</td>' +
                                '<td class="currency">' + account.card_currency + '</td>' +
                                '<td class="fin_limit">' + account.fin_limit + '</td>' +
                                '</tr>';
                        });
                        accounts_table += "\t\t</tbody>\n" +
                            "\t\t</table>";

                        $('#wallets .monobank_cards').html(accounts_table).show();
                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    });

    $(document).on('keyup', '.privat_bank_code, .privat_bank_merchant_id', function (e) {
        let merchant_code = $('.privat_bank_code').val();
        let merchant_id = $('.privat_bank_merchant_id').val();
        console.log(merchant_code.length);
        console.log(merchant_id.length);
        if (merchant_code.length === 32 && merchant_id.length === 6) {
            $.ajax({
                type: "POST",
                url: '/wallet/get_privat24_balance_ajax',
                data: {
                    merchant_id: merchant_id,
                    merchant_code: merchant_code
                },
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        $('.actual_balance #actual_balance').val(data.balance.toFixed(2));
                        $('.actual_balance #card_currency').val(data.currency);
                        $('.actual_balance').show();
                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    });


    // $(document).on('click', '#wallets .monobank_cards table tbody tr', function (e) {
    //     console.log($(this).find('[type=radio]'));
    //     $(this).find('[type=radio]').trigger('select');
    // });

    $(document).on('change', '#wallets .monobank_cards table tbody tr [type=radio]', function (e) {
        let tr = $(this).parents('tr');
        let id = tr.find('.id').text();
        console.log(tr);
        let balance = tr.find('.balance').text();
        let currency = tr.find('.currency').text();
        let fin_limit = tr.find('.fin_limit').text();
        console.log(balance);
        console.log(currency);
        $('.actual_balance #actual_balance').val(balance);
        $('.actual_balance #card_currency').val(currency);
        $('.actual_balance [name=merchant_id]').val(id);
        $('.actual_balance').show();
    });


    $(document).on('click', '#wallets .add_wallet', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            url: '/wallet/add_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data)
                console.log(data);
                if (data.status === 'ok') {
                    location.reload();
                } else {
                    alert(data.message);
                }
            }
        });
    });

    $('html').on('click', '.delete_wallet', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей гаманець і операції здійснені через нього?" +
            "Якщо по ньому є якісь операції - це призведе до помилок в обрахунках ")) {
            $.ajax({
                type: "POST",
                url: '/wallet/delete_ajax/' + btn.parents('tr').attr('data-wallet_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $(document).on('click', '.open_wallet_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('wallet_sidenav', 300);
        // fill_operation_sidenav();
    });
});

