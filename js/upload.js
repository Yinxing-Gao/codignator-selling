function upload_files(files, url, name = 'my_file_upload', callback) {
    console.log(files);
    // ничего не делаем если files пустой
    if (typeof files == 'undefined') return;

    // создадим объект данных формы
    let data = new FormData();

    // заполняем объект данных файлами в подходящем для отправки формате
    $.each(files, function (key, value) {
        data.append(key, value);
    });

    // добавим переменную для идентификации запроса
    data.append(name, 1);

    // AJAX запрос
    $.ajax({
        url: url,
        type: 'POST', // важно!
        data: data,
        cache: false,
        dataType: 'json',
        // отключаем обработку передаваемых данных, пусть передаются как есть
        processData: false,
        // отключаем установку заголовка типа запроса. Так jQuery скажет серверу что это строковой запрос
        contentType: false,
        beforeSend: function () {
            $('.uploaded_file').html('<img class="ajax_loader" src="../../img/ajax-loader.gif" />')
        },
        // функция успешного ответа сервера
        success: function (respond, status, jqXHR) {
            if (!callback) {
                console.log(respond);
                // ОК - файлы загружены
                if (typeof respond.error === 'undefined') {
                    // выведем пути загруженных файлов в блок '.ajax-reply'
                    let files_path = respond.files;
                    let html = '';
                    let files_arr = [];
                    $.each(files_path, function (key, val) {
                        let name = val.split('/');
                        let name_str = name[name.length - 1];
                        if (name_str.length > 30) {
                            name_str = '...' + name_str.substr(name_str.length - 30);
                        }

                        html += '<span>' +
                            '<a href="' + val + '" class="order_file" target="_blank"> ' + name_str + '</a>' +
                            '<img class="delete_uploaded_file" src="../../../icons/bootstrap/trash.svg"/>' +
                            '</span>';
                        files_arr.push(val);
                    });
                    $('.upload_files.button').html('Завантажено');
                    $('.ajax-reply').val(JSON.stringify(files_arr));

                    $('.uploaded_file').html(html);
                }
                // ошибка
                else {
                    console.log('ОШИБКА: ' + respond.error);
                }
            } else {
                callback(respond, status, jqXHR);
            }
        },
        // функция ошибки ответа сервера
        error: function (jqXHR, status, errorThrown) {
            console.log('ОШИБКА AJAX запроса: ' + status, jqXHR);
        }

    });
}