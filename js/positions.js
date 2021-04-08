$(document).ready(function () {

    $('html').on('change', '#position_list #department_id', function (e) {
        window.location = '/position/index/' + $(this).val();
    });

    $(document).on('click', '.btn_add_position', function (event) {
        event.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/position/add_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '.delete_position', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цю посаду? ")) {
            $.ajax({
                type: "POST",
                url: '/position/delete_ajax/' + btn.parents('tr').attr('data-position_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $('#position_list .position_users').select2({
        placeholder: 'Співробітники',
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
                        return {
                            text: item.name + ' ' + item.surname,
                            id: item.id,
                        }
                    })
                };
            },
        }
    });

    $('html').on('change', '#position_list .position_users', function (e) {
        let select = $(this);
        if (confirm("Ви впевнені, що хочете змінити співробітників на цій посаді? ")) {
            $.ajax({
                type: "POST",
                url: '/position/change_users_ajax/' + select.parents('tr').attr('data-position_id'),
                data: {
                    users: select.val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $(document).on('click', '.open_position_sidenav', function (event) {
        event.preventDefault();
        // $(this).addClass('is-active');
        openNav('position_sidenav', 300);
        // fill_operation_sidenav();
    });

    if ($('#org_chart').length > 0) {
        console.log('yes');
        let api_key = $('#api_key').val();
        $.ajax({
            type: "POST",
            // dataType: "html",
            url: '/position/get_org_ajax/' + api_key,
            success: function (data) {
                data = JSON.parse(data);
                let nodes_ = [
                    {
                        id: "1",
                        name: "Jack Hill",
                        title: "Chairman and CEO",
                        email: "amber@domain.com",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "2",
                        pid: "1",
                        name: "Lexie Cole",
                        title: "QA Lead",
                        email: "ava@domain.com",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "3",
                        pid: "1",
                        name: "Janae Barrett",
                        title: "Technical Director",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "4",
                        pid: "1",
                        name: "Aaliyah Webb",
                        title: "Manager",
                        email: "jay@domain.com",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "5",
                        pid: "2",
                        name: "Elliot Ross",
                        title: "QA",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "6",
                        pid: "2",
                        name: "Anahi Gordon",
                        title: "QA",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "7",
                        pid: "2",
                        name: "Knox Macias",
                        title: "QA",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "8",
                        pid: "3",
                        name: "Nash Ingram",
                        title: ".NET Team Lead",
                        email: "kohen@domain.com",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "9",
                        pid: "3",
                        name: "Sage Barnett",
                        title: "JS Team Lead",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "10",
                        pid: "8",
                        name: "Alice Gray",
                        title: "Programmer",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "11",
                        pid: "8",
                        name: "Anne Ewing",
                        title: "Programmer",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "12",
                        pid: "9",
                        name: "Reuben Mcleod",
                        title: "Programmer",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "13",
                        pid: "9",
                        name: "Ariel Wiley",
                        title: "Programmer",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "14",
                        pid: "4",
                        name: "Lucas West",
                        title: "Marketer",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "15",
                        pid: "4",
                        name: "Adan Travis",
                        title: "Designer",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    },
                    {
                        id: "16",
                        pid: "4",
                        name: "Alex Snider",
                        title: "Sales Manager",
                        img: "https://app.fineko.space//icons/fineko/positions.svg"
                    }
                ];

                let chart = new OrgChart(document.getElementById("org_chart"), {
                    template: "diva",
                    layout: OrgChart.mixed,
                    nodeBinding: {
                        img_0: "img",
                        field_0: "name",
                        field_1: "title",
                        field_2: "link"
                    },
                    nodes: data

                });
                document.getElementById("org_chart").addEventListener("change", function () {
                    chart.config.template = this.value;
                    chart.draw();
                });
            }
        });
    }

    $('html').on('change', '#position_list .subordination', function (e) {
        let storage_id = $(this).val();
        $.ajax({
            type: "POST",
            url: '/storage/get_names_ajax/' + storage_id,
            success: function (data) {
                data = JSON.parse(data);
                let names = '';
                data.forEach(function (name) {
                    names += '<span class="storage_names_for_purchase" ' +
                        'data_id="' + name.id + '" ' +
                        'data_price="' + name.buy_price + '" ' +
                        'data_amount = "' + name.amount + '"' +
                        'data_unit = "' + name.unit + '">' +
                        name.name + '</span>';
                });
                if (names.length > 0) {
                    $('.storage_names_for_purchase_block').html(names);
                } else {
                    $('.storage_names_for_purchase_block').html('<p>На цьому складі поки немає найменувань. Додайте найменування, будь ласка, <a href="/storage/add_names/' + storage_id + '">тут</a>');
                }
            }
        });
    });
});
