'use strict';
jQuery(document).ready(function () {
    let $uiAccordion = $('.js-ui-accordion');

    $uiAccordion.accordion({
        collapsible: true,
        heightStyle: 'content',

        activate: function activate(event, ui) {
            let newHeaderId = ui.newHeader.attr('id');

            if (newHeaderId) {
                history.pushState(null, null, '#' + newHeaderId);
            }
        },

        create: function create(event, ui) {
            let $this = $(event.target);
            let $activeAccordion = $(window.location.hash);

            if ($this.find($activeAccordion).length) {
                $this.accordion('option', 'active', $this.find($this.accordion('option', 'header')).index($activeAccordion));
            }
        }
    });

    $(window).on('hashchange', function (event) {
        let $activeAccordion = $(window.location.hash);
        let $parentAccordion = $activeAccordion.parents('.js-ui-accordion');

        if ($activeAccordion.length) {
            $parentAccordion.accordion('option', 'active', $parentAccordion.find($uiAccordion.accordion('option', 'header')).index($activeAccordion));
        }
    });

    let files; // переменная. будет содержать данные файлов
    $('input[type=file]').on('change', function (event) {
        event.stopPropagation(); // остановка всех текущих JS событий
        event.preventDefault();  // остановка дефолтного события для текущего элемента - клик для <a> тега

        files = this.files;
        upload_files(files, '/excel/upload_users_ajax', 'users', function (response) {
            if (response.status === 'ok') {
                $('#users_table').append('<tr class="success"><td colspan="4">' + response.message + '</td></tr>')
                $('.uploaded_file').html('');
            }
        })
    });

    // $('.upload_users').on('click', function (event) {
    //     event.preventDefault();
    //     $.ajax({
    //         type: "POST",
    //         url: '/excel/,
    //         data: {
    //             category: $(this).val(),
    //         },
    //         success: function (data) {
    //             data = JSON.parse(data);
    //             select.parents('tr').attr('class', '');
    //             select.parents('tr').addClass('app_row_cat_' + select.val())
    //         }
    //     });
    // });
});