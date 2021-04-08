let current_user_id = $('.task_user_id').val();
$(".task_user_id").select2({
    placeholder: 'Виконавець',
    customClass: "form-control",
    escapeMarkup: function (markup) {
        return markup;
    },
    ajax: {
        url: '/user/get_ajax',
        dataType: "json",
        type: 'POST',
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    if (item.id === current_user_id) {
                        return {
                            id: item.id,
                            text: $('.task_user_id').html(),
                            options: item.options
                        }
                    } else {
                        return {
                            id: item.id,
                            text: item.name + ' ' + item.surname,
                            options: item.options
                        }
                    }
                })
            }
        }
    }
});

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$('html').on('click', '.add_new_task_btn', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');

    $.ajax({
        // type: "get",
        type: 'POST',
        url: '/tasks/add_task_ajax',
        //data: form.serializeObject(),
        data: {
            task: form.find('[name=task]').val(),
            description: form.find('[name=description]').val(),
            user_id: form.find('[name=user_id]').val(),
            statistic: form.find('[name=statistic]').val(),
            date_to: form.find('[name=date_to]').val(),
            notify: form.find('[name=notify]').val()
        },
        success: function (data) {
            //data = JSON.parse(data);

        }
    });
    return false;
});