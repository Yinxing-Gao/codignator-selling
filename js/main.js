jQuery(document).ready(function () {

    $(".tablesorter").tablesorter();
    //
    // $.ajax({
    //     type: "POST",
    //     url: '/start/questionnaire',
    //     success: function (data) {
    //         console.log(data);
    //
    //         // if (data.status === 'ok') {
    //         console.log(data);
    //         if(data.length > 0) {
    //             data = JSON.parse(data);
    //             open_popup(data, 'questionnaire');
    //         }
    //         // }
    //     }
    // });

    if($('.doublescroll').length > 0){
        $('.doublescroll').doubleScroll();
    }

    $(document).on('click', '.top_mobile_menu_btn', function (event) {
        event.preventDefault();
        if ($(this).find('img').length > 0 || $(this).attr('data-id') === 'all') {
            openNav($(this).attr('data-id') + '_sidenav');
        }
    });

    $(document).on('click', '.close_nav_btn', function (event) {
        event.preventDefault();
        closeNav();
    });

//user/login


    //popup
    $('html').on('click', '.popup .close', function () { // .overlay
        close_popup();
    });

    $(' .play_video_instruction').click(function (e) {
        e.preventDefault();
        let href = $(this).attr('href');
        // console.log($('.popup').height());
        // console.log($('.popup').width());
        // // let width = parseInt(jQuery(window).width()) - 100;
        // let height =  parseInt(jQuery(window).height()) - 100;
        //
        // let width = parseInt(height * 560/315);
        // console.log(height);
        // console.log(width);
        let height = 630;
        let width = 1220;

        let popup_content = '<iframe width="' + width + '" height="' + height + '" src="' + href + '" frameborder="0" allowfullscreen></iframe>';
        open_popup(popup_content)
    });
    $(document).on('click', '.close_notification', function (event) {
        $(this).parents('.notification').remove();
    });

    $(document).on('click', '.open_desktop_sidenav', function (event) {
        event.preventDefault();
        $(this).addClass('is-active');
        openNav('desktop_sidenav', 250);
    });

    $(document).on('click', '.sidenav#desktop_sidenav li .svg', function (event) {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
            $(this).parent().removeClass('open');
        } else {
            $(this).addClass('open');
            $(this).parent().addClass('open');
        }
    });

    $(document).on('click', '.sidenav#desktop_sidenav li.main_menu_item > a', function (event) {
        event.preventDefault();
        console.log('test');
        $(this).parents('li.main_menu_item').find('.svg').trigger('click');
    });
// operations in menu

    $(document).on('click', '.open_operation_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('operation_sidenav', 300);
        fill_operation_sidenav();
    });


// project in menu
    $(document).on('click', '.open_project_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('project_sidenav', 300);
        fill_project_sidenav();
    });

    // accruals in menu
    $(document).on('click', '.open_accrual_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('accruals_sidenav', 300);
    });

    $(document).on('click', '.accruals_sidenav_btns .btn', function () {
        $('#accruals_sidenav .panel').hide();
        $('.accruals_sidenav_btns .btn').removeClass('btn-dark');
        $(this).addClass('btn-dark');
        let panel = $(this).attr('href').substr(1);
        $('#panel_' + panel).show();
    });

    $(document).on('click', '.open_task_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('tasks_sidenav', 300);
        //fill_project_sidenav();
    });

    /******************************************* calculator ************************************************/
    $(document).on('click', '.open_calculator_popup', function (event) {
        event.preventDefault();
        $('#calculator').show();
    });

    $(document).on('click', '#calculator .close_calc', function (event) {
        event.preventDefault();
        $('#calculator').hide();
    });

    $(document).on('click', '.add_contractor_from_select2', function (event) {
        let new_contractor_name = '';
        $('.select2-search__field').each(function (index) {
            if ($(this).val().length > 0) {
                new_contractor_name = $(this).val();
            }
        });

        let button = $(this);
        let contractor_type = button.attr('data-contractor_type');
        let selector = button.attr('data-selector');

        event.preventDefault();
        $.ajax({
            type: "POST",
            url: '/contractor/add_ajax',
            data: {
                name: new_contractor_name,
                contractor_type: contractor_type,
                account_id: $('#account_id').val()
            },
            success: function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    let newOption = new Option(new_contractor_name, data.id, false, true);
                    $(selector).append(newOption).trigger('change');
                    $(selector).select2('close');
                } else {
                    alert(data.message);
                }
            }
        });
    });

    // /******************************************* chat ************************************************/
});

function close_popup() {
    $('.overlay, .popup').hide();
    $('.popup').attr('style', '');
    $('.popup .popup_content').html('');
}

function open_popup(html, type = 'normal') {
    switch (type) {
        case 'questionnaire':
            $('.popup_content').html("" +
                "<div class='questionnaire'>" +
                "<div class='row'>" +
                "<div class='col-md-7 content'>" +
                html +
                "<div class='question_action_button'><input class='form-control question_done btn-success' type='submit' value='Далі'/></div>" +
                "</div>" +
                "<div class='col-md-5 questionnaire_pic'>" +
                "<img src='../img/bot.png'/>" +
                "</div>" +
                "</div>" +
                "</div>").show();
            $('.overlay, .popup').show();
            break;
        default:
            $('.popup_content').html(html).show();
            $('.overlay, .popup').show();
    }
}

function open_small_popup(html, width, height) {
    $('.popup_content').html(html);
    $('.popup').css('width', width + 'px').css('height', height + 'px');
    $('.overlay, .popup').show();
    $('.popup_content').show();
}

function notification(html) {
    let timerId = setTimeout(function () {
        $('#notification_' + timerId).hide(2000, function () {
        });
    }, 4000);
    let notification = '<span class="alert alert-success notification" id="notification_' + timerId + '">' + html + '<span class="close_notification">X</span></span>';
    $('.notification_block').append(notification);

}

function setLocation(curLoc) {
    try {
        history.pushState(null, null, curLoc);
        return;
    } catch (e) {
    }
    location.hash = '#' + curLoc;
}

function count_total(panel) {
    let total = 0;
    $('#panel_' + panel + ' .amount_left_to').each(function (index) {
        let td = $(this);
        total += (parseInt(td.text()));
    });
    $('#total').html(total + ' грн');
}

function count_total_checked(panel) {
    let checked_total = 0;
    $('#panel_' + panel + ' .amount_left_to').each(function (index) {
        let td = $(this);
        if (td.parent('tr').find('input[type=checkbox]').is(':checked')) {
            checked_total += (parseInt(td.text()));
        }
    });
    $('#total_left_to').html(checked_total + ' грн');
}

function create_numbers(panel) {
    let html = '';
    switch (panel) {
        case "all":
            html = '\t<div>Загальна сума: <span id="total" class="badge badge-success">0 грн</span></div>\n' +
                '\t\t\t<div>Обрано: <span id="total_left_to" class="badge badge-success">0 грн</span></div>\n';
            // +'\t\t\t<div>В касі: <span id="account" class="badge badge-success">0 грн</span></div>';
            break;
        case "cash":
            html = '\t<div>Загальна сума (готівка): <span id="total" class="badge badge-success">0 грн</span></div>\n' +
                '\t\t\t<div>Обрано: <span id="total_left_to" class="badge badge-success">0 грн</span></div>\n';
            // + '\t\t\t<div>В касі ( готівка ): <span id="account" class="badge badge-success">0 грн</span></div>';
            break;
        case "tov":
            html = '\t<div>Загальна сума (на ТОВ): <span id="total" class="badge badge-success">0 грн</span></div>\n' +
                '\t\t\t<div>Обрано: <span id="total_left_to" class="badge badge-success">0 грн</span></div>\n';
            // +'\t\t\t<div>В касі ( ТОВ ): <span id="account" class="badge badge-success">0 грн</span></div>'
            break;
        default:
            html = '\t<div>Загальна сума: <span id="total" class="badge badge-success">0 грн</span></div>\n' +
                '\t\t\t<div>Обрано: <span id="total_left_to" class="badge badge-success">0 грн</span></div>\n' +
                '\t\t\t<div>В касі: <span id="account" class="badge badge-success">0 грн</span></div>';
            break;
    }
    $('#numbers').html(html);


}

function getWeekDay(date) {
    let days = ['Субота', 'Неділя', 'Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П\'ятниця'];
    return days[date.getDay()];
}

function openNav(id, width = 250) {
    closeNav();
    document.getElementById(id).style.width = width + "px";
}

function closeNav() {
    $('.sidenav').css('width', '0');
    $('.open_desktop_sidenav').removeClass('is-active');

}
