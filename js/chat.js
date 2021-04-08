// let domain = window.location.hostname;
// let socket = new WebSocket("wss://" + domain + "/telegram/chat");
//
// console.log("wss://" + domain + "/telegram/chat");
//
// socket.onopen = function(e) {
//     alert("[open] Соединение установлено");
//     alert("Отправляем данные на сервер");
//     socket.send("Меня зовут Джон");
// };
//
// socket.onmessage = function(event) {
//     alert(`[message] Данные получены с сервера: ${event.data}`);
// };
//
// socket.onclose = function(event) {
//     if (event.wasClean) {
//         alert(`[close] Соединение закрыто чисто, код=${event.code} причина=${event.reason}`);
//     } else {
//         // например, сервер убил процесс или сеть недоступна
//         // обычно в этом случае event.code 1006
//         alert('[close] Соединение прервано');
//     }
// };
//
// socket.onerror = function(error) {
//     alert(`[error] ${error.message}`);
// };

// function showMessage(messageHTML) {
//     $('#chat-box').append(messageHTML);
// }

$(document).ready(function () {
    let chat_body = $('.telegram_chat_body');
    $(document).on('click', '.open_telegram_chat', function (event) {
        event.preventDefault();
        let user_id = $('#user_id').val();
        $('#telegram_chat').show();
        $.ajax({
            type: "POST",
            url: '/telegram/get_chat_messages_ajax/' + user_id,
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    $.each(data.messages, function (index, message) {
                        console.log(message);
                        
                        if (message.type === "chat_message") {
                            let message_row = '<div class="chat_message darker">\n' +
                                '<img src="../icons/bootstrap/person-circle.svg" alt="Avatar" class="right" style="width:100%;">\n' +
                                '<p class="text-right">' + message.text + '</p>\n' +
                                '<span class="time-left">' + message.time + '</span>\n' +
                                '</div>';
                            chat_body.append(message_row);
                        }

                        if (message.type === "chat_reply") {
                            let message_row = '<div class="chat_message">\n' +
                                '<img src="../img/logo%20no%20text%20no%20background.png" alt="Avatar" style="width:100%;">\n' +
                                '<p>' + message.text + '</p>\n' +
                                '<span class="time-right">' + message.time + '</span>\n' +
                                '</div>';
                            chat_body.append(message_row);
                        }
                        chat_body.scrollTop(chat_body.prop('scrollHeight'));
                    });
                } else {
                    alert(data.message);
                }
            }
        });


        //
        //     let domain = window.location.hostname;
        //     let websocket = new WebSocket("wss://" + domain + "/telegram/chat");
        //     websocket.onopen = function(event) {
        //         showMessage("<div class='chat-connection-ack'>Connection is established!</div>");
        //     };
        //     websocket.onmessage = function(event) {
        //         let Data = JSON.parse(event.data);
        //         showMessage("<div class='"+Data.message_type+"'>"+Data.message+"</div>");
        //         $('#chat-message').val('');
        //     };
        //
        //     websocket.onerror = function(event){
        //         showMessage("<div class='error'>Problem due to some Error</div>");
        //     };
        //     websocket.onclose = function(event){
        //         showMessage("<div class='chat-connection-ack'>Connection Closed</div>");
        //     };
        //
        //     console.log(websocket);
        //
        //     // $('#frmChat').on("submit",function(event){
        //     //     event.preventDefault();
        //         // $('#chat-user').attr("type","hidden");
        //         // let messageJSON = {
        //         //     chat_user: $('#chat-user').val(),
        //         //     chat_message: $('#chat-message').val()
        //         // };
        //         websocket.send('test'); // відправка повідомлень
        //     // });
    });


    let interval_id = 0;
    $(document).on('click', '.send_chat_message', function (event) {
        event.preventDefault();
        clearTimeout(interval_id);

        let message = $('.chat_message_input').val();
        let user_id = $('#user_id').val();

        $.ajax({
            type: "POST",
            url: '/telegram/chat_send_message_ajax/' + user_id,
            data: {
                message: message
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    $('.chat_message_input').val('');
                    let message_row = '<div class="chat_message darker">\n' +
                        '<img src="../icons/bootstrap/person-circle.svg" alt="Avatar" class="right" style="width:100%;">\n' +
                        '<p>' + data.message + '</p>\n' +
                        '<span class="time-left">' + data.time + '</span>\n' +
                        '</div>';
                    chat_body.append(message_row);
                    console.log('test');
                    chat_body.scrollTop(chat_body.prop('scrollHeight'));

                    interval_id = setInterval(function () {
                        $.ajax({
                            type: "POST",
                            url: '/telegram/check_chat_reply_ajax/' + user_id,
                            data: {
                                timestamp: data.timestamp
                            },
                            success: function (data_) {
                                data_ = JSON.parse(data_);
                                if (data_.status === 'ok') {
                                    $.each(data_.messages, function (index, message) {
                                        let message_row = '<div class="chat_message">\n' +
                                            '<img src="../img/logo%20no%20text%20no%20background.png" alt="Avatar" style="width:100%;">\n' +
                                            '<p>' + message.text + '</p>\n' +
                                            '<span class="time-right">' + message.time + '</span>\n' +
                                            '</div>';
                                        chat_body.append(message_row);
                                        chat_body.scrollTop(chat_body.prop('scrollHeight'));
                                    });
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

    let can_send = true;
    $(document).on('focus', '.chat_message_input', function (event) {
        $(window).on('keydown', function (e) {
            if (can_send) {
                let code = e.which || e.keyCode;
                if (code === 13) {
                    $('.send_chat_message').trigger('click');
                    can_send = false;
                    let timer_id = setTimeout(function tick() {
                        can_send = true;
                    }, 500);
                }
            }
        });
    });

    $(document).on('click', '.close_chat', function (event) {
        event.preventDefault();
        $('#telegram_chat').hide();
        clearTimeout(interval_id);
        // $('.open_telegram_chat').show();
    });
});