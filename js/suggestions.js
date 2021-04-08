jQuery(document).ready(function () {
    $(document).on('click', '.btn_add_suggestion, .btn_add_bug', function (event) {
        event.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/info/add_suggestion_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '.delete_suggestion', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цю рекомендацію? ")) {
            $.ajax({
                type: "POST",
                url: '/info/delete_suggestion_ajax/' + btn.parents('tr').attr('data-suggestion_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $('html').on('change', '#all_bugs_and_suggestions .status', function (e) {
        let suggestion_id = $(this).parents('tr').attr('data-suggestion_id');
        let status = $(this).val();
        $.ajax({
            type: "POST",
            url: '/info/change_suggestion_status_ajax/' + suggestion_id,
            data: {
                status: status
            },
            success: function (data) {
                data = JSON.parse(data);
                notification('Статус успішно змінено');
                location.reload();
            }
        });
    });
});

