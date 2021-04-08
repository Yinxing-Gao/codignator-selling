$(document).ready(function () {

    $(document).on('click', '.open_telegram_sidenav', function (event) {
        event.preventDefault();
        openNav('telegram_sidenav', 300);
    });

    $(document).on('click', '.generate_telegram_code', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '/telegram/generate_code_ajax',
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    $('#telegram_code').val(data.code).show();
                    $('.telegram_code').show();
                    $('.telegram_go_to_bot').show();

                    let intervalId = setInterval(function () {
                        $.ajax({
                            type: "POST",
                            url: '/telegram/check_code_ajax',
                            success: function (data) {
                                data = JSON.parse(data);
                                console.log(data);
                                if (data.status === 'ok') {
                                    clearInterval(intervalId);
                                    open_small_popup('Успішно добавлено', 300, 200);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                }
                            }
                        });
                    }, 1000);
                } else {
                    alert(data.message);
                }
            }
        });
    });
});

