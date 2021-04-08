$(document).ready(function () {

    $(document).on('click', '.open_article_sidenav', function (event) {
        event.preventDefault();
        openNav('article_sidenav', 300);
    });

    $(document).on('click', '.open_article_templates_sidenav', function (event) {
        event.preventDefault();
        openNav('article_templates_sidenav', 300);
    });

    $('#article_tree').on('tree.move', function (event) {
            event.preventDefault();
            // do the move first, and _then_ POST back.
            event.move_info.do_move();
            console.log($(this));
            console.log($(this).tree('toJson'));
            // $.post('your_url', {tree: $(this).tree('toJson')});
            $.ajax({
                type: "POST",
                url: '/articles/update_articles_tree_ajax',
                data: {
                    tree: $(this).tree('toJson')
                },
                success: function (data) {
                    data = JSON.parse(data);
                }
            });
        }
    );
    //
    // $('#income_tree').on('tree.move', function (event) {
    //         event.preventDefault();
    //         // do the move first, and _then_ POST back.
    //         event.move_info.do_move();
    //         console.log($(this));
    //         console.log($(this).tree('toJson'));
    //         // $.post('your_url', {tree: $(this).tree('toJson')});
    //         $.ajax({
    //             type: "POST",
    //             url: '/articles/update_articles_tree_ajax/1',
    //             data: {
    //                 tree: $(this).tree('toJson')
    //             },
    //             success: function (data) {
    //                 data = JSON.parse(data);
    //             }
    //         });
    //     }
    // );


    $('html').on('click', '.delete_article', function (e) {
        e.preventDefault();
        let btn = $(this);
        if (confirm("Ви впевнені, що хочете видалити цю cтаттю і її дочірніх? ")) {
            $.ajax({
                type: "POST",
                url: '/articles/delete_ajax/' + btn.parents('tr').attr('data-article_id'),
                success: function (data) {
                    data = JSON.parse(data);
                    location.reload();
                }
            });
        }
    });

    $(document).on('click', '.btn_add_article', function (event) {
        event.preventDefault();
        let form = $(this).closest('form');
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/articles/add_ajax',
            data: form.serialize(),
            success: function (data) {
                data = JSON.parse(data);
                location.reload();
            }
        });
    });

    $('html').on('click', '#select_all_articles', function (e) {
        let checkbox = $(this);
        let table = checkbox.parents('table');
        table.find('.check').each(function (index) {
            $(this).prop('checked', checkbox.prop("checked"));
        });
    });


    $('html').on('click', '.templates_table .check', function (e) {
        let checkbox = $(this);
        if (checkbox.prop("checked") === true) {
            find_parent(checkbox)
        }
        if (checkbox.prop("checked") === false) {
            find_children(checkbox)
        }
    });

    $('html').on('click', '.add_articles_from_template', function (e) {
        e.preventDefault();
        let department_id = $(this).parents('form').find('[name=department_id]').val();
        if (department_id !== '') {
            let ids = [];
            $('.templates_table .check').each(function (index) {
                if ($(this).prop("checked") === true) {
                    ids.push($(this).parents('tr').attr('data-article_id'));
                }
            });

            $.ajax({
                type: "POST",
                method: "POST",
                url: '/articles/add_from_template_ajax',
                data: {
                    ids: ids,
                    department_id: department_id,
                    account_id: $('#account_id').val()
                },
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.status === 'ok') {
                        location.href = "/articles/index/" + department_id;
                    }
                    // location.reload();
                }
            });
        }
    });


    // var data = [
    //     {
    //         name: 'node1', id: 1,
    //         children: [
    //             { name: 'child1', id: 2 },
    //             { name: 'child2', id: 3 }
    //         ]
    //     },
    //     {
    //         name: 'node2', id: 4,
    //         children: [
    //             { name: 'child3', id: 5 }
    //         ]
    //     }
    // ];
    //
    // $tree.tree({
    //     dragAndDrop: true,
    //     autoOpen: 0,
    //     data: data
    // });


    $('html').on('change', '#article_list #department_id', function (e) {
        window.location = '/articles/index/' + $(this).val();
    });

    $('html').on('change', '#article_list .is_shown', function (e) {
        let checkbox = $(this);
        if ($(this).prop("checked") === true) {
        }
        $.ajax({
            type: "POST",
            method: "POST",
            url: '/articles/change_visibility_ajax/' + checkbox.parents('tr').attr('data-article_id'),
            data: {
                is_shown: checkbox.prop("checked") === true ? 1 : 0
            },
            success: function (data) {
                // data = JSON.parse(data);
                // location.reload();
            }
        });
    });

    $('html').on('click', '#go_to_templates', function (e) {
        e.preventDefault();
        window.location = $(this).attr('href') + '/' + $('#department_id').val();
    });

    $(document).on('click', '.open_tree', function (event) {
        event.preventDefault();
        let id = $(this).parents('tr').attr('data-article_id');
        let type = $(this).parents('tr').attr('data-type');
        $.ajax({
            type: "POST",
            url: '/articles/tree_ajax/' + id,
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                open_popup(data);
                $.ajax({
                    type: "POST",
                    url: '/articles/get_articles_tree_ajax/' + id,
                    data: {
                        type: type
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        $('#article_tree').tree({
                            dragAndDrop: true,
                            autoOpen: 0,
                            data: data,
                            onCreateLi: function (node, $li) {
                                console.log(node);
                                console.log(node.parent);
                                console.log($('#article_tree_id').val());
                                let parent_id = node.parent.parent !== null ? node.parent.id : $('#article_tree_id').val();
                                $.ajax({
                                    type: "POST",
                                    url: '/articles/update_articles_tree_ajax/' + node.id,
                                    data: {
                                        parent_id: parent_id
                                    },
                                    success: function (data) {
                                        data = JSON.parse(data);
                                    }
                                });
                                // Append a link to the jqtree-element div.
                                // The link has an url '#node-[id]' and a data property 'node-id'.
                                $li.find('.jqtree-element').append(
                                    '<br/><span class="description">' + node.description + '</span>'
                                );
                                // '<img class="delete_article" data_id="'+ node.id +'" src="../../img/trash-icon.jpg"/>'
                            }
                        });
                        //
                        // $('#income_tree').tree({
                        //     dragAndDrop: true,
                        //     autoOpen: 0,
                        //     data: data.income,
                        //     // onCreateLi: function(node, $li) {
                        //     //     // Append a link to the jqtree-element div.
                        //     //     // The link has an url '#node-[id]' and a data property 'node-id'.
                        //     //     $li.find('.jqtree-element').append(
                        //     //         // '<a href="#node-'+ node.id +'" class="edit" data-node-id="'+ node.id +'">edit</a>'
                        //     //         '<img class="delete_article" data_id="'+ node.id +'" src="../../img/trash-icon.jpg"/>'
                        //     //     );
                        //     // }
                        // });
                    }
                });
            }
        });
    });
});


function find_parent(checkbox) {
    let table = checkbox.parents('table');
    let parent_id = checkbox.parents('tr').attr('data-parent_id');
    table.find('tbody tr').each(function (index) {
        if ($(this).attr('data-article_id') === parent_id) {
            $(this).find('.check').prop('checked', true);
            let older_parent_id = $(this).attr('data-parent_id');
            if (older_parent_id !== 0) {
                find_parent($(this).find('.check'));
            }
        }
    });
}


function find_children(checkbox) {
    let table = checkbox.parents('table');
    let id = checkbox.parents('tr').attr('data-article_id');

    table.find('tbody tr').each(function (index) {
        if ($(this).attr('data-parent_id') === id) {
            $(this).find('.check').prop('checked', false);
            find_children($(this).find('.check'));
        }
    });
}
