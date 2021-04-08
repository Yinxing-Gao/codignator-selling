$(document).ready(function () {
    $(document).on('click', '.btn_add_storage', function (event) {
        event.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/storage/add_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '.delete_storage', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цей склад? ")) {
            $.ajax({
                type: "POST",
                url: '/storage/delete_ajax/' + btn.parents('tr').attr('data-storage_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    // for open storage nav sidebar
    $(document).on('click', '.open_storage_sidenav', function (event) {
        event.preventDefault();
        openNav('storage_sidenav', 300);
    });


    
});
