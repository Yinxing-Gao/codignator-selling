$(document).ready(function () {
    // CKEDITOR.replace( 'instruction' );

    // ClassicEditor.create(document.querySelector('#instruction')).catch(error => {
    //     console.error(error);
    // });

    let myEditor;

    ClassicEditor
        .create(document.querySelector('#instruction'))
        .then(editor => {
            console.log('Editor was initialized', editor);
            myEditor = editor;
            console.log(myEditor.getData());
        })
        .catch(err => {
            console.error(err.stack);
        });
    console.log($(this).closest('form').serialize());

    $('html').on('click', '.save_instruction', function (e) {
        e.preventDefault();
        console.log(myEditor.getData());
        let btn = $(this);
        let instruction_id = btn.attr('data-instruction_id');
        $.ajax({
            type: "POST",
            url: '/instruction/save_job_ajax/' + instruction_id,
            data:
                {
                    text: myEditor.getData()
                },
            success: function (data) {
                data = JSON.parse(data);
                console.log(data);
                notification('Зміни успішно збережено');
            }
        });
    });

});


// let myEditor;
//
// // CKEDITOR
// //     .create( document.querySelector( '#instruction' ) )
// //     .then( editor => {
// //         console.log( 'Editor was initialized', editor );
// //         myEditor = editor;
// //     } )
// //     .catch( err => {
// //         console.error( err.stack );
// //     } );
// //
// // CKEDITOR.getData();
//
// const ckEditorClassicOptions = {
//     toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'link', 'insertTable', 'undo', 'redo'],
//     heading: {
//         options: [
//             { model: 'paragraph', title: 'Параграф' },
//             //{ model: 'heading1', view: 'h1', title: 'Heading 1' },
//             { model: 'heading2', view: 'h2', title: 'Заголовок 2' },
//             { model: 'heading3', view: 'h3', title: 'Заголовок 3' },
//             { model: 'heading4', view: 'h4', title: 'Заголовок 4' },
//             { model: 'heading5', view: 'h5', title: 'Заголовок 5' }
//         ]
//     }
// };
//
// const ckEditorClassicOptionsMin = {
//     toolbar: ['bold', 'italic']
// };
//
// var allCkEditors = [];
// $(document).ready(function() {
//     // Initialize All CKEditors
//     allCkEditors = [];
//
//     var allHtmlElements = document.querySelectorAll('.ck-classic');
//     for (var i = 0; i < allHtmlElements.length; ++i) {
//         ClassicEditor
//             .create(allHtmlElements[i], ckEditorClassicOptions)
//             .then(editor => {
//                 allCkEditors.push(editor);
//             })
//             .catch(error => {
//                 console.error(error);
//             });
//     }
//
//     allHtmlElements = document.querySelectorAll('.ck-classic-min');
//     for (var j = 0; j < allHtmlElements.length; ++j) {
//         ClassicEditor
//             .create(allHtmlElements[j], ckEditorClassicOptionsMin)
//             .then(editor => {
//                 allCkEditors.push(editor);
//             })
//             .catch(error => {
//                 console.error(error);
//             });
//     }
//
// });
//
//
// ckEditor("instruction").getData()
// function ckEditor(name) {
//     for (var i = 0; i < allCkEditors.length; i++) {
//         if (allCkEditors[i].sourceElement.id === name) return allCkEditors[i];
//     }
//
//     return null;
// }
