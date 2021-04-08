$(document).ready(function () {

    $(document).on('click', '.btn-login', function (event) {
        event.preventDefault();
        if ($('#login_input').val().length > 0 || $('#password_input').val().length > 0) {
            $.ajax({
                type: "POST",
                url: 'login_ajax',
                data: {
                    login: $('#login_input').val(),
                    password: $('#password_input').val(),

                },
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        window.location = '/';
                    } else {
                        alert(data.message);
                    }
                }
            });
        } else {
            alert("Введіть, будь ласка, логін і пароль");
        }
    });
//user/password
    $(document).on('click', '.btn-password', function (event) {
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/user/passwordajax/' + $('#id').val(),
            data: {
                login: $('#login_input').val(),
                password: $('#password_input').val(),

            },
            success: function (data) {
                data = JSON.parse(data);
                console.log(data.status);
                // if (data.status === 'ok') {
                window.location = '/user/login';
                // } else {
                //     alert(data.message);
                // }
            }
        });
    });

    //registration
    $(document).on('click', '.btn-reg', function (event) {
            event.preventDefault();
            let form = $(this).closest('form');
            form.find('input').removeClass('error');
            form.find('span.error').remove();
            let errors = [];
            if ($('#registration [name=login]').val() <= 3) {
                errors.push({
                    element: $('#registration [name=login]'),
                    message: 'Введіть, будь ласка, логін, який ви будете використовувати для входу.'
                })
            }

            // if ($('#registration [name=email]').val().length < 6) {
            //     errors.push('Введіть, будь ласка, еmail')
            // }

            if ($('#registration [name=name]').val().length < 3) {
                errors.push({
                    element: $('#registration [name=name]'),
                    message: 'Введіть, будь ласка, ваше ім\'я'
                });
            }

            if ($('#registration [name=surname]').val().length < 3) {
                errors.push({
                    element: $('#registration [name=surname]'),
                    message: 'Введіть, будь ласка, ваше прізвище'
                });
            }

            if ($('#registration [name=password]').val().length < 3) {
                errors.push({
                    element: $('#registration [name=password]'),
                    message: 'Введіть, будь ласка, пароль, який буде використовуватися для входу'
                });
            }

            if ($('#registration [name=password2]').val().length < 3) {
                errors.push({
                    element: $('#registration [name=password2]'),
                    message: 'Повторіть, будь ласка, пароль'
                });
            }


            if ($('#registration [name=password]').val() !== $('#registration [name=password2]').val()) {
                errors.push({
                    element: $('#registration [name=password2]'),
                    message: 'Паролі не співпадають'
                });
            }

            if (errors.length === 0) {
                $.ajax({
                    type: "POST",
                    url: '/user/registration_ajax',
                    data: form.serialize(),
                    success: function (data) {
                        console.log(data);
                        data = JSON.parse(data);
                        if (data.status === 'ok') {
                            window.location = '/';
                        } else {
                            alert(data.message);
                        }
                    }
                });
            } else {
                $.each(errors, function (index, error) {
                    error.element.addClass('error').after('<span class="error">' + error.message + '</span>');
                });
            }
        }
    );
});
